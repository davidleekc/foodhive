<?php

namespace Database\Seeders;

use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use MetaBox;

class FlashSaleSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('flash-sales');

        FlashSale::truncate();
        DB::table('ec_flash_sales_translations')->truncate();
        DB::table('ec_flash_sale_products')->truncate();
        MetaBoxModel::where('reference_type', FlashSale::class)->delete();

        $data = [
            ['name' => 'Deal of the Day'],
            ['name' => 'Flash sale 2'],
            ['name' => 'Flash sale 3'],
            ['name' => 'Flash sale 4'],
            ['name' => 'Flash sale 5'],
        ];

        $productIds = Product::where('is_variation', 0)->pluck('id')->all();

        foreach ($data as $index => $item) {
            $item['end_date'] = now()
                ->addDays(rand(15, 50))
                ->addHours(rand(1, 23))
                ->addMinutes(rand(1, 59))
                ->toDateString();

            $flashSale = FlashSale::create($item);

            MetaBox::saveMetaBoxData($flashSale, 'image', 'flash-sales/' . ($index + 1) . '.png');

            $product = Product::find(Arr::random($productIds));

            $price = $product->price;

            if ($product->front_sale_price !== $product->price) {
                $price = $product->front_sale_price;
            }

            $flashSale->products()->attach([
                $product->id => [
                    'price' => $price - ($price * rand(10, 70) / 100),
                    'quantity' => rand(6, 20),
                    'sold' => rand(1, 5),
                ],
            ]);
        }

        $translations = [
            ['name' => 'Ưu đãi trong ngày'],
            ['name' => 'Khuyến mãi 2'],
            ['name' => 'Khuyến mãi 3'],
            ['name' => 'Khuyến mãi 4'],
            ['name' => 'Khuyến mãi 5'],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ec_flash_sales_id'] = $index + 1;

            DB::table('ec_flash_sales_translations')->insert($item);

            MetaBox::saveMetaBoxData(FlashSale::find($index + 1), 'vi_image', 'flash-sales/' . ($index + 1) . '.png');
        }

        $flashSale = FlashSale::create([
            'name' => 'Daily Best Sales',
            'end_date' => now()->addDays(30)->toDateString(),
        ]);

        for ($i = 1; $i <= 20; $i++) {
            $product = Product::where('id', $i)->where('is_variation', 0)->first();

            if (! $product) {
                continue;
            }

            $price = $product->price;

            if ($product->front_sale_price !== $product->price) {
                $price = $product->front_sale_price;
            }

            $flashSale->products()->attach([
                $product->id => [
                    'price' => $price - ($price * rand(10, 70) / 100),
                    'quantity' => rand(6, 20),
                    'sold' => rand(1, 5),
                ],
            ]);
        }

        DB::table('ec_flash_sales_translations')->insert([
            'name' => 'Bán tốt nhất hàng ngày',
            'lang_code' => 'vi',
            'ec_flash_sales_id' => 6,
        ]);
    }
}
