<?php

use Botble\Ecommerce\Models\Product;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $products = Product::where('is_variation', 0)->with('variations')->get();

        foreach ($products as $product) {
            Product::whereIn('id', $product->variations->pluck('product_id')->all())
                ->where('is_variation', 1)
                ->update(['name' => $product->name]);
        }
    }
};
