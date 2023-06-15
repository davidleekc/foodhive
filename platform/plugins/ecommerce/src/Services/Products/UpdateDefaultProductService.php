<?php

namespace Botble\Ecommerce\Services\Products;

use Botble\Ecommerce\Models\Product;

class UpdateDefaultProductService
{
    public function execute(Product $product)
    {
        $parent = $product->original_product;

        if (! $parent->id) {
            return null;
        }

        $data = [
            'sku',
            'sale_type',
            'price',
            'sale_price',
            'start_date',
            'end_date',
            'length',
            'wide',
            'height',
            'weight',
        ];

        foreach ($data as $item) {
            $parent->{$item} = $product->{$item};
        }

        if ($parent->sale_price > $parent->price) {
            $parent->sale_price = null;
        }

        if ($parent->sale_type == 0) {
            $parent->start_date = null;
            $parent->end_date = null;
        }

        $parent->save();

        return $parent;
    }
}
