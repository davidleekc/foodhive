<?php

namespace Database\Seeders;

use Botble\Ads\Models\Ads;
use Botble\Base\Supports\BaseSeeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MetaBox;

class AdsSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('promotion');

        Ads::truncate();

        $items = [
            [
                'name' => 'Everyday Fresh',
                'location' => 'not_set',
                'key' => 'IZ6WU8KUALYD',
                'subtitle' => "Everyday Fresh & \nClean with Our \nProducts",
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'Make your Breakfast',
                'location' => 'not_set',
                'key' => 'ILSFJVYFGCPZ',
                'subtitle' => 'Make your Breakfast Healthy and Easy',
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'The best Organic',
                'location' => 'not_set',
                'key' => 'ILSDKVYFGXPH',
                'subtitle' => 'The best Organic Products Online',
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'Bring nature into your home',
                'location' => 'not_set',
                'key' => 'IZ6WU8KUALYG',
                'subtitle' => 'Bring nature into your home',
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'Delivered to your home',
                'location' => 'banner-big',
                'key' => 'IZ6WU8KUALYH',
                'subtitle' => 'Delivered to your home',
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'Save 17% on Oganic Juice',
                'location' => 'no_set',
                'key' => 'IZ6WU8KUALYI',
                'subtitle' => 'Save 17% <br />on Oganic <br />Juice',
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'Everyday Fresh & Clean with Our Products',
                'location' => 'item-style-4',
                'key' => 'IZ6WU8KUALYJ',
                'subtitle' => 'Everyday Fresh & Clean with Our Products',
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'The best Organic Products Online',
                'location' => 'item-style-5',
                'key' => 'IZ6WU8KUALYK',
                'subtitle' => 'The best Organic Products Online',
                'button_text' => 'Shop now',
                'url' => '/products',
            ],
            [
                'name' => 'Everyday Fresh with Our Products',
                'location' => 'item-style-2',
                'key' => 'IZ6WU8KUALYL',
                'subtitle' => "Everyday Fresh with\n Our Products",
                'button_text' => 'Go to supplier',
            ],
            [
                'name' => '100% guaranteed all Fresh items',
                'location' => 'item-style-2',
                'key' => 'IZ6WU8KUALYM',
                'subtitle' => "100% guaranteed all\n Fresh items",
                'button_text' => 'Go to supplier',
            ],
            [
                'name' => 'Special grocery sale off this month',
                'location' => 'item-style-2',
                'key' => 'IZ6WU8KUALYN',
                'subtitle' => "Special grocery sale\n off this month",
                'button_text' => 'Go to supplier',
            ],
            [
                'name' => 'Enjoy 15% OFF for all vegetable and fruit',
                'location' => 'item-style-2',
                'key' => 'IZ6WU8KUALYO',
                'subtitle' => "Enjoy 15% OFF for all\n vegetable and fruit",
                'button_text' => 'Go to supplier',
            ],
        ];

        foreach ($items as $index => $item) {
            $item['order'] = $index + 1;

            if (! isset($item['key'])) {
                $item['key'] = strtoupper(Str::random(12));
            }

            $item['expired_at'] = now()->addYears(5)->toDateString();
            $item['image'] = 'promotion/' . ($index + 1) . '.png';

            $ad = Ads::create(Arr::except($item, ['subtitle', 'button_text']));

            MetaBox::saveMetaBoxData($ad, 'button_text', $item['button_text']);
            MetaBox::saveMetaBoxData($ad, 'subtitle', $item['subtitle']);
        }

        DB::table('ads_translations')->truncate();

        $translations = [
            [
                'name' => 'Tươi mỗi ngày',
                'subtitle' => "Sạch sẽ hàng ngày với \ncác sản phẩm của \nchúng tôi",
                'button_text' => 'Mua ngay',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Nấu bữa sáng của bạn',
                'subtitle' => 'Nẫu bữa sáng của bạn lành mạnh và dễ dàng',
                'button_text' => 'Mua ngay',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Hữu cơ tốt nhất',
                'subtitle' => 'Các sản phẩm hữu cơ tốt nhất trực tuyến',
                'button_text' => 'Mua ngay',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Mang thiên nhiên vào ngôi nhà của bạn',
                'subtitle' => 'Mang thiên nhiên vào ngôi nhà của bạn',
                'button_text' => 'Mua ngay',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Giao tận nhà',
                'subtitle' => 'Giao tận nhà',
                'button_text' => 'Mua ngay',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Tiết kiệm 17% khi mua nước ép hữu cơ',
                'subtitle' => 'Tiết kiệm 17% <br /> khi mua Nước ép <br /> Oganic',
                'button_text' => 'Mua ngay',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Tươi và Sạch hàng ngày với các sản phẩm của chúng tôi',
                'subtitle' => 'Tươi và Sạch hàng ngày với các sản phẩm của chúng tôi',
                'button_text' => 'Shop now',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Các sản phẩm hữu cơ tốt nhất trực tuyến',
                'subtitle' => 'Các sản phẩm hữu cơ tốt nhất trực tuyến',
                'button_text' => 'Shop now',
                'url' => '/vi/products',
            ],
            [
                'name' => 'Tươi mới mỗi ngày với các sản phẩm của chúng tôi',
                'subtitle' => "Tươi mới mỗi ngày với\n các sản phẩm của chúng tôi",
                'button_text' => 'Đi đến nhà cung cấp',
            ],
            [
                'name' => '100% đảm bảo tất cả các mặt hàng tươi',
                'subtitle' => "100% đảm bảo tất cả\n các mặt hàng tươi",
                'button_text' => 'Đi đến nhà cung cấp',
            ],
            [
                'name' => 'Giảm giá hàng tạp hóa đặc biệt trong tháng này',
                'subtitle' => "Giảm giá hàng tạp hóa\n đặc biệt trong tháng này",
                'button_text' => 'Đi đến nhà cung cấp',
            ],
            [
                'name' => 'GIẢM GIÁ 15% cho tất cả rau và trái cây',
                'subtitle' => "GIẢM GIÁ 15% cho tất cả\n rau và trái cây",
                'button_text' => 'Đi đến nhà cung cấp',
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['ads_id'] = $index + 1;
            $item['image'] = 'promotion/' . ($index + 1) . '.png';
            $item['url'] = '/vi/products';

            DB::table('ads_translations')->insert(Arr::except($item, ['subtitle', 'button_text']));

            MetaBox::saveMetaBoxData(Ads::find($index + 1), 'vi_subtitle', $item['subtitle']);
            MetaBox::saveMetaBoxData(Ads::find($index + 1), 'vi_button_text', $item['button_text']);
        }
    }
}
