<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\ProductAttribute;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Illuminate\Support\Facades\DB;

class ProductAttributeSeeder extends BaseSeeder
{
    public function run(): void
    {
        ProductAttributeSet::truncate();

        ProductAttributeSet::create([
            'title' => 'Weight',
            'slug' => 'weight',
            'display_layout' => 'text',
            'is_searchable' => true,
            'is_use_in_product_listing' => true,
            'order' => 0,
        ]);

        ProductAttributeSet::create([
            'title' => 'Boxes',
            'slug' => 'boxes',
            'display_layout' => 'text',
            'is_searchable' => true,
            'is_use_in_product_listing' => true,
            'order' => 1,
        ]);

        ProductAttribute::truncate();

        $productAttributes = [
            [
                'attribute_set_id' => 1,
                'title' => '1KG',
                'slug' => '1kg',
                'is_default' => true,
                'order' => 1,
            ],
            [
                'attribute_set_id' => 1,
                'title' => '2KG',
                'slug' => '2kg',
                'is_default' => false,
                'order' => 2,
            ],
            [
                'attribute_set_id' => 1,
                'title' => '3KG',
                'slug' => '3kg',
                'is_default' => false,
                'order' => 3,
            ],
            [
                'attribute_set_id' => 1,
                'title' => '4KG',
                'slug' => '4kg',
                'is_default' => false,
                'order' => 4,
            ],
            [
                'attribute_set_id' => 1,
                'title' => '5KG',
                'slug' => '5kg',
                'is_default' => false,
                'order' => 5,
            ],
            [
                'attribute_set_id' => 2,
                'title' => '1 Box',
                'slug' => '1box',
                'is_default' => true,
                'order' => 1,
            ],
            [
                'attribute_set_id' => 2,
                'title' => '2 Boxes',
                'slug' => '2boxes',
                'is_default' => false,
                'order' => 2,
            ],
            [
                'attribute_set_id' => 2,
                'title' => '3 Boxes',
                'slug' => '3boxes',
                'is_default' => false,
                'order' => 3,
            ],
            [
                'attribute_set_id' => 2,
                'title' => '4 Boxes',
                'slug' => '4boxes',
                'is_default' => false,
                'order' => 4,
            ],
            [
                'attribute_set_id' => 2,
                'title' => '5 Boxes',
                'slug' => '5boxes',
                'is_default' => false,
                'order' => 5,
            ],
        ];

        foreach ($productAttributes as $item) {
            ProductAttribute::create($item);
        }

        if (is_plugin_active('language')) {
            DB::table('ec_product_attributes_translations')->truncate();
            DB::table('ec_product_attribute_sets_translations')->truncate();

            DB::table('ec_product_attribute_sets_translations')->insert([
                'title' => 'Cân nặng',
                'ec_product_attribute_sets_id' => 1,
                'lang_code' => 'vi',
            ]);

            DB::table('ec_product_attribute_sets_translations')->insert([
                'title' => 'Số hộp',
                'ec_product_attribute_sets_id' => 2,
                'lang_code' => 'vi',
            ]);

            $translations = [
                [
                    'title' => '1KG',
                ],
                [
                    'title' => '2KG',
                ],
                [
                    'title' => '3KG',
                ],
                [
                    'title' => '4KG',
                ],
                [
                    'title' => '5KG',
                ],
                [
                    'title' => '1 Hộp',
                ],
                [
                    'title' => '2 Hộp',
                ],
                [
                    'title' => '3 Hộp',
                ],
                [
                    'title' => '4 Hộp',
                ],
                [
                    'title' => '5 Hộp',
                ],
            ];

            foreach ($translations as $index => $item) {
                $item['lang_code'] = 'vi';
                $item['ec_product_attributes_id'] = $index + 1;

                DB::table('ec_product_attributes_translations')->insert($item);
            }
        }
    }
}
