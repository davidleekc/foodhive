<?php

namespace Database\Seeders;

use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Slug\Models\Slug;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MetaBox;
use SlugHelper;

class ProductCategorySeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('product-categories');

        $categories = [
            [
                'name' => 'Milks and Dairies',
            ],
            [
                'name' => 'Clothing & beauty',
            ],
            [
                'name' => 'Pet Toy',
            ],
            [
                'name' => 'Baking material',
            ],
            [
                'name' => 'Fresh Fruit',
            ],
            [
                'name' => 'Wines & Drinks',
            ],
            [
                'name' => 'Fresh Seafood',
            ],
            [
                'name' => 'Fast food',
            ],
            [
                'name' => 'Vegetables',
            ],
            [
                'name' => 'Bread and Juice',
            ],
            [
                'name' => 'Cake & Milk',
            ],
            [
                'name' => 'Coffee & Teas',
            ],
            [
                'name' => 'Pet Foods',
            ],
            [
                'name' => 'Diet Foods',
            ],
        ];

        ProductCategory::truncate();
        DB::table('ec_product_categories_translations')->truncate();
        Slug::where('reference_type', ProductCategory::class)->delete();
        MetaBoxModel::where('reference_type', ProductCategory::class)->delete();

        foreach ($categories as $index => $item) {
            $this->createCategoryItem($index, $item);
        }

        $translations = [
            [
                'name' => 'Sữa',
            ],
            [
                'name' => 'Quần áo và làm đẹp',
            ],
            [
                'name' => 'Đồ chơi thú cưng',
            ],
            [
                'name' => 'Nguyên liệu làm bánh',
            ],
            [
                'name' => 'Trái cây tươi',
            ],
            [
                'name' => 'Rượu & Đồ uống',
            ],
            [
                'name' => 'Hải sản tươi sống',
            ],
            [
                'name' => 'Đồ ăn nhanh',
            ],
            [
                'name' => 'Rau',
            ],
            [
                'name' => 'Bánh và đồ uống',
            ],
            [
                'name' => 'Bánh và sữa',
            ],
            [
                'name' => 'Cà phê và trà',
            ],
            [
                'name' => 'Đồ ăn thú nuôi',
            ],
            [
                'name' => 'Đồ ăn kiêng',
            ],
        ];

        $count = 1;
        foreach ($translations as $translation) {
            $translation['lang_code'] = 'vi';
            $translation['ec_product_categories_id'] = $count;

            DB::table('ec_product_categories_translations')->insert(Arr::except($translation, ['children']));

            if (isset($translation['children']) && is_array($translation['children']) && ! empty($translation['children'])) {
                foreach ($translation['children'] as $child) {
                    $child['lang_code'] = 'vi';
                    $child['ec_product_categories_id'] = $count + 1;

                    DB::table('ec_product_categories_translations')->insert($child);

                    $count++;
                }
            }

            $count++;
        }
    }

    protected function colors(): array
    {
        return [
            '#F2FCE4',
            '#FFFCEB',
            '#ECFFEC',
            '#FEEFEA',
            '#FFF3EB',
            '#FFF3FF',
        ];
    }

    protected function createCategoryItem(int $index, array $category, int $parentId = 0): void
    {
        $category['parent_id'] = $parentId;
        $category['order'] = $index;
        $category['is_featured'] = $index < 12;
        $category['image'] = 'product-categories/image-' . ($index + 1) . '.png';

        if (Arr::has($category, 'children')) {
            $children = $category['children'];
            unset($category['children']);
        } else {
            $children = [];
        }

        $createdCategory = ProductCategory::create($category);

        Slug::create([
            'reference_type' => ProductCategory::class,
            'reference_id' => $createdCategory->id,
            'key' => Str::slug($createdCategory->name),
            'prefix' => SlugHelper::getPrefix(ProductCategory::class),
        ]);

        MetaBox::saveMetaBoxData($createdCategory, 'icon_image', 'product-categories/icon-' . ($index + 1) . '.png');
        MetaBox::saveMetaBoxData($createdCategory, 'background_color', Arr::get($this->colors(), $index % count($this->colors())));

        if ($children) {
            foreach ($children as $childIndex => $child) {
                $this->createCategoryItem($childIndex, $child, $createdCategory->id);
            }
        }
    }
}
