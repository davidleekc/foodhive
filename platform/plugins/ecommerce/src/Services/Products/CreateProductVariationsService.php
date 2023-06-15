<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;

class CreateProductVariationsService
{
    public function __construct(
        protected ProductInterface $productRepository,
        protected ProductAttributeInterface $productAttributeRepository,
        protected ProductVariationInterface $productVariationRepository
    ) {
    }

    public function execute(Product $product): array
    {
        $attributeSets = $product->productAttributeSets()->allRelatedIds()->toArray();

        $attributes = $this->productAttributeRepository
            ->advancedGet([
                'condition' => [['attribute_set_id', 'IN', $attributeSets]],
            ]);

        $data = [];

        foreach ($attributeSets as $attributeSet) {
            $data[] = $attributes
                ->where('attribute_set_id', $attributeSet)
                ->pluck('id')
                ->toArray();
        }

        $variationsInfo = $this->combinations($data);

        $variations = [];
        foreach ($variationsInfo as $value) {
            $result = $this->productVariationRepository->getVariationByAttributesOrCreate($product->id, $value);
            $variations[] = $result['variation'];
        }

        return $variations;
    }

    protected function combinations(array $array): array
    {
        $result = [[]];

        foreach ($array as $key => $value) {
            $tmp = [];
            foreach ($result as $item) {
                foreach ($value as $valueItem) {
                    $tmp[] = array_merge($item, [$key => $valueItem]);
                }
            }
            $result = $tmp;
        }

        return $result;
    }
}
