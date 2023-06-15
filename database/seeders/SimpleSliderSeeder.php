<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\SimpleSlider\Models\SimpleSlider;
use Botble\SimpleSlider\Models\SimpleSliderItem;
use Illuminate\Support\Arr;
use MetaBox;

class SimpleSliderSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('sliders');

        SimpleSlider::truncate();
        SimpleSliderItem::truncate();
        LanguageMeta::where('reference_type', SimpleSlider::class)->delete();

        $sliders = [
            'en_US' => [
                [
                    'name' => 'Home slider 1',
                    'key' => 'home-slider-1',
                    'total' => 2,
                    'style' => 'style-4',
                ],
                [
                    'name' => 'Home slider 2',
                    'key' => 'home-slider-2',
                    'total' => 2,
                    'style' => 'style-2',
                ],
                [
                    'name' => 'Home slider 3',
                    'key' => 'home-slider-3',
                    'total' => 2,
                    'style' => 'style-3',
                ],
                [
                    'name' => 'Home slider 4',
                    'key' => 'home-slider-4',
                    'total' => 2,
                    'style' => 'style-1',
                ],
                [
                    'name' => 'Home slider 5',
                    'key' => 'home-slider-5',
                    'total' => 2,
                    'style' => 'style-5',
                ],
                [
                    'name' => 'Home slider 6',
                    'key' => 'home-slider-6',
                    'total' => 1,
                    'style' => 'style-6',
                ],
                [
                    'name' => 'Blog slider 1',
                    'key' => 'blog-slider-1',
                    'total' => 6,
                    'style' => 'style-1',
                    'gallery' => true,
                ],
            ],
            'vi' => [
                [
                    'name' => 'Slider trang chủ 1',
                    'key' => 'slider-trang-chu-1',
                    'total' => 2,
                    'style' => 'style-4',
                ],
                [
                    'name' => 'Slider trang chủ 2',
                    'key' => 'slider-trang-chu-2',
                    'total' => 2,
                    'style' => 'style-2',
                ],
                [
                    'name' => 'Slider trang chủ 3',
                    'key' => 'slider-trang-chu-3',
                    'total' => 2,
                    'style' => 'style-3',
                ],
                [
                    'name' => 'Slider trang chủ 4',
                    'key' => 'slider-trang-chu-4',
                    'total' => 2,
                    'style' => 'style-1',
                ],
                [
                    'name' => 'Slider trang chủ 5',
                    'key' => 'slider-trang-chu-5',
                    'total' => 2,
                    'style' => 'style-5',
                ],
                [
                    'name' => 'Slider trang chủ 6',
                    'key' => 'slider-trang-chu-6',
                    'total' => 1,
                    'style' => 'style-6',
                ],
                [
                    'name' => 'Slider blog 1',
                    'key' => 'slider-blog-1',
                    'total' => 6,
                    'style' => 'style-1',
                    'gallery' => true,
                ],
            ],
        ];

        $sliderItems = [
            'en_US' => [
                [
                    'title' => 'Don’t miss amazing<br /> grocery deals',
                    'link' => '/products',
                    'description' => 'Sign up for the daily newsletter',
                ],
                [
                    'title' => 'Fresh Vegetables<br />
										Big discount',
                    'link' => '/products',
                    'description' => 'Save up to 50% off on your first order',
                ],
            ],
            'vi' => [
                [
                    'title' => 'Đừng bỏ lỡ <br /> tuyệt vời giao dịch hàng tạp hóa',
                    'link' => '/products',
                    'description' => 'Tiết kiệm hơn với mã giảm giá 70%',
                ],
                [
                    'title' => 'Rau tươi <br />Giảm giá lớn',
                    'link' => '/products',
                    'description' => 'Tiết kiệm đến 50% cho đơn đặt hàng đầu tiên của bạn',
                ],
            ],
        ];

        foreach ($sliders as $locale => $sliderItem) {
            foreach ($sliderItem as $index => $value) {
                $slider = SimpleSlider::create(Arr::only($value, ['name', 'key']));

                $originValue = null;

                if ($locale !== 'en_US') {
                    $originValue = LanguageMeta::where([
                        'reference_id' => $index + 1,
                        'reference_type' => SimpleSlider::class,
                    ])->value('lang_meta_origin');
                }

                LanguageMeta::saveMetaData($slider, $locale, $originValue);

                if (Arr::get($value, 'style')) {
                    MetaBox::saveMetaBoxData($slider, 'simple_slider_style', $value['style']);
                }

                if (Arr::get($value, 'gallery')) {
                    for ($i = 1; $i <= $value['total']; $i++) {
                        $item = [
                            'image' => 'sliders/thumbnail-' . $i . '.jpg',
                            'order' => $i,
                            'simple_slider_id' => $slider->id,
                        ];

                        SimpleSliderItem::create($item);
                    }
                } else {
                    foreach (collect($sliderItems[$locale])->take($value['total']) as $key => $item) {
                        $item['image'] = 'sliders/' . ($index + 1) . '-' . ($key + 1) . '.png';
                        $item['order'] = $key + 1;
                        $item['simple_slider_id'] = $slider->id;

                        SimpleSliderItem::create($item);
                    }
                }
            }
        }
    }
}
