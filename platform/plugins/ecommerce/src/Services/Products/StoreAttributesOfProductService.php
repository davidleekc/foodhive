<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductVariationInterface;

class StoreAttributesOfProductService
{
    public function __construct(
        protected ProductAttributeInterface $productAttributeRepository,
        protected ProductVariationInterface $productVariationRepository
    ) {
    }

    public function execute(Product $product, array $attributeSets, array $attributes = []): Product
    {
        $product->productAttributeSets()->sync($attributeSets);

        if (! $attributes) {
            $attributes = $this->productAttributeRepository
                ->getModel()
                ->whereIn('attribute_set_id', $attributeSets)
                ->pluck('id')
                ->all();

            $attributes = $this->getSelectedAttributes($product, $attributes);
        }

        $this->productVariationRepository->correctVariationItems($product->id, $attributes);

        return $product;
    }

    protected function getSelectedAttributes(Product $product, array $attributes): array
    {
        $attributeSets = $product->productAttributeSets()
            ->select('attribute_set_id')
            ->pluck('attribute_set_id')
            ->toArray();

        $allRelatedAttributeBySet = $this->productAttributeRepository
            ->allBy([
                ['attribute_set_id', 'IN', $attributeSets],
            ], [], ['id'])
            ->pluck('id')
            ->toArray();

        $newAttributes = [];

        foreach ($attributes as $item) {
            if (in_array($item, $allRelatedAttributeBySet)) {
                $newAttributes[] = $item;
            }
        }

        return $newAttributes;
    }
}
