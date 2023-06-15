<?php

namespace Botble\Ecommerce\Http\Requests;

use Botble\Support\Http\Requests\Request;

class ProductCollectionRequest extends Request
{
    public function rules(): array
    {
        return match (request()->route()->getName()) {
            'product-collections.create' => [
                'name' => 'required',
                'slug' => 'required|unique:ec_product_collections',
            ],
            default => [
                'name' => 'required',
            ],
        };
    }
}
