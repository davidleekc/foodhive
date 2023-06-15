<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Brand;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SlugHelper;

class BrandSeeder extends BaseSeeder
{
    public function run(): void
    {
        $brands = [
            [
                'name' => 'Perxsion',
            ],
            [
                'name' => 'Hiching',
            ],
            [
                'name' => 'Kepslo',
            ],
            [
                'name' => 'Groneba',
            ],
            [
                'name' => 'Babian',
            ],
            [
                'name' => 'Valorant',
            ],
            [
                'name' => 'Pure',
            ],
        ];

        Brand::truncate();
        Slug::where('reference_type', Brand::class)->delete();

        foreach ($brands as $key => $item) {
            $item['order'] = $key;
            $item['is_featured'] = true;
            $brand = Brand::create($item);

            Slug::create([
                'reference_type' => Brand::class,
                'reference_id' => $brand->id,
                'key' => Str::slug($brand->name),
                'prefix' => SlugHelper::getPrefix(Brand::class),
            ]);
        }

        DB::table('ec_brands_translations')->truncate();

        $translations = [
            [
                'name' => 'Perxsion',
            ],
            [
                'name' => 'Hiching',
            ],
            [
                'name' => 'Kepslo',
            ],
            [
                'name' => 'Groneba',
            ],
            [
                'name' => 'Babian',
            ],
            [
                'name' => 'Valorant',
            ],
            [
                'name' => 'Pure',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ec_brands_id'] = $index + 1;

            DB::table('ec_brands_translations')->insert($item);
        }
    }
}
