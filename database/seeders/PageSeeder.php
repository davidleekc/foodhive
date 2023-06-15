<?php

namespace Database\Seeders;

use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Supports\BaseSeeder;
use Botble\Language\Models\LanguageMeta;
use Botble\Page\Models\Page;
use Botble\Slug\Models\Slug;
use Faker\Factory;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MetaBox;
use SlugHelper;

class PageSeeder extends BaseSeeder
{
    public function run(): void
    {
        $faker = Factory::create();

        $themeAds = Html::tag(
            'div',
            '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
        );
        $popularProducts = Html::tag('div', '[popular-products title="Popular Products" per_row="4" limit="8"][/popular-products]');
        $bestFlashSale = Html::tag(
            'div',
            '[best-flash-sale title="Daily Best Sells" flash_sale_id="6" ads="IZ6WU8KUALYG"][/best-flash-sale]'
        );
        $topProductsGroup = Html::tag('div', '[top-products-group tabs="top-selling,trending-products,recent-added,top-rated" top_selling_in_days="365"][/top-products-group]');
        $flashSale = Html::tag(
            'div',
            '[flash-sale flash_sale_1="2" flash_sale_2="3" flash_sale_3="4" flash_sale_4="5" title="Deals Of The Day" flash_sale_popup_id="1"][/flash-sale]'
        );
        $simpleSlider1 = Html::tag(
            'div',
            '[simple-slider key="home-slider-1" show_newsletter_form="yes"][/simple-slider]'
        );
        $productCategories = Html::tag('div', '[product-categories title="Shop by Categories"][/product-categories]');
        $simpleSlider2 = Html::tag(
            'div',
            '[simple-slider key="home-slider-2" ads_1="IZ6WU8KUALYH" show_newsletter_form="yes" cover_image="sliders/banner-1.png"][/simple-slider]'
        );
        $featuredProductCategories = Html::tag(
            'div',
            '[featured-product-categories title="Top Categories"][/featured-product-categories]'
        );
        $simpleSlider5 = Html::tag(
            'div',
            '[simple-slider key="home-slider-5" ads_1="IZ6WU8KUALYJ" ads_2="IZ6WU8KUALYK" show_newsletter_form="yes"][/simple-slider]'
        );
        $themeAds2 = Html::tag(
            'div',
            '[theme-ads ads_1="IZ6WU8KUALYL" ads_2="IZ6WU8KUALYM" ads_3="IZ6WU8KUALYN" ads_4="IZ6WU8KUALYO" style="style-5"][/theme-ads]'
        );
        $bigBanner = Html::tag(
            'div',
            '[big-banner cover_image="general/home-6.jpeg" show_newsletter_form="yes" number_display_featured_categories="4" title="What are you looking for?"][/big-banner]'
        );
        $trendingProducts = Html::tag('div', '[trending-products title="Trending items" limit="20"][/trending-products]');

        $pages = [
            [
                'name' => 'Homepage',
                'content' =>
                    $simpleSlider1 .
                    $featuredProductCategories .
                    $themeAds .
                    $popularProducts .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup,
                'template' => 'homepage',
            ],
            [
                'name' => 'Homepage 2',
                'content' =>
                    $simpleSlider2 .
                    $themeAds .
                    $popularProducts .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup .
                    $productCategories,
                'template' => 'homepage',
            ],
            [
                'name' => 'Homepage 3',
                'content' =>
                    $simpleSlider1 .
                    $popularProducts .
                    $flashSale .
                    $themeAds .
                    $productCategories .
                    $topProductsGroup,
                'template' => 'homepage',
            ],
            [
                'name' => 'Homepage 4',
                'content' =>
                    Html::tag(
                        'div',
                        '[simple-slider key="home-slider-4" show_newsletter_form="yes"][/simple-slider]'
                    ) .
                    $popularProducts .
                    $flashSale .
                    $themeAds .
                    $productCategories .
                    $topProductsGroup,
                'template' => 'homepage',
            ],
            [
                'name' => 'Blog',
                'content' => Html::tag('p', '---'),
                'template' => 'blog-grid',
            ],
            [
                'name' => 'Contact',
                'content' => Html::tag('p', '[google-map]502 New Street, Brighton VIC, Australia[/google-map]') .
                    Html::tag('p', '[our-offices][/our-offices]') .
                    Html::tag('p', '[contact-form][/contact-form]'),
            ],
            [
                'name' => 'About us',
                'content' =>
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
                'template' => 'right-sidebar',
            ],
            [
                'name' => 'Cookie Policy',
                'content' => Html::tag('h3', 'EU Cookie Consent') .
                    Html::tag(
                        'p',
                        'To use this website we are using Cookies and collecting some data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.'
                    ) .
                    Html::tag('h4', 'Essential Data') .
                    Html::tag(
                        'p',
                        'The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.'
                    ) .
                    Html::tag(
                        'p',
                        '- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.'
                    ) .
                    Html::tag(
                        'p',
                        '- XSRF-Token Cookie: Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.'
                    ),
            ],
            [
                'name' => 'Terms & Conditions',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Returns & Exchanges',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Shipping & Delivery',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Privacy Policy',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Blog List',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
                'template' => 'blog-list',
            ],
            [
                'name' => 'Blog Big',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
                'template' => 'blog-big',
            ],
            [
                'name' => 'Blog Wide',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
                'template' => 'blog-wide',
            ],
            [
                'name' => 'Homepage 5',
                'content' =>
                    $simpleSlider5 .
                    $featuredProductCategories .
                    $themeAds .
                    $popularProducts .
                    $themeAds2 .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup,
                'template' => 'homepage',
                'header_style' => 'header-style-5',
            ],
            [
                'name' => 'Homepage 6',
                'content' =>
                    $bigBanner .
                    $trendingProducts .
                    $flashSale .
                    $topProductsGroup,
                'template' => 'homepage',
                'header_style' => 'header-style-5',
            ],
            [
                'name' => 'Faq',
                'content' => Html::tag('div', '[faqs][/faqs]'),
            ],
        ];

        Page::truncate();
        DB::table('pages_translations')->truncate();
        Slug::where('reference_type', Page::class)->delete();
        MetaBoxModel::where('reference_type', Page::class)->delete();
        LanguageMeta::where('reference_type', Page::class)->delete();

        foreach ($pages as $item) {
            $item['user_id'] = 1;

            if (! isset($item['template'])) {
                $item['template'] = 'default';
            }

            $page = Page::create(Arr::except(
                $item,
                ['header_style', 'expanding_product_categories_on_the_homepage']
            ));

            $headerStyle = isset($item['header_style']) ? $item['header_style'] : null;
            if ($headerStyle) {
                MetaBox::saveMetaBoxData($page, 'header_style', $headerStyle);
            }

            if (isset($item['expanding_product_categories_on_the_homepage'])) {
                MetaBox::saveMetaBoxData(
                    $page,
                    'expanding_product_categories_on_the_homepage',
                    $item['expanding_product_categories_on_the_homepage']
                );
            }

            Slug::create([
                'reference_type' => Page::class,
                'reference_id' => $page->id,
                'key' => Str::slug($page->name),
                'prefix' => SlugHelper::getPrefix(Page::class),
            ]);
        }

        $themeAds = Html::tag(
            'div',
            '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
        );
        $popularProducts = Html::tag('div', '[popular-products title="Sản phẩm phổ biến" per_row="4" limit="8"][/popular-products]');
        $bestFlashSale = Html::tag(
            'div',
            '[best-flash-sale title="Bán chạy nhất hàng ngày" flash_sale_id="6" ads="IZ6WU8KUALYG"][/best-flash-sale]'
        );
        $topProductsGroup = Html::tag('div', '[top-products-group tabs="top-selling,trending-products,recent-added,top-rated" top_selling_in_days="365"][/top-products-group]');
        $flashSale = Html::tag(
            'div',
            '[flash-sale flash_sale_1="2" flash_sale_2="3" flash_sale_3="4" flash_sale_4="5" title="Ưu đãi trong ngày" flash_sale_popup_id="1"][/flash-sale]'
        );
        $simpleSlider1 = Html::tag(
            'div',
            '[simple-slider key="home-slider-1" show_newsletter_form="yes"][/simple-slider]'
        );
        $productCategories = Html::tag(
            'div',
            '[product-categories title="Mua sắm theo danh mục"][/product-categories]'
        );
        $simpleSlider2 = Html::tag(
            'div',
            '[simple-slider key="home-slider-2" ads_1="IZ6WU8KUALYH" show_newsletter_form="yes" cover_image="sliders/banner-1.png"][/simple-slider]'
        );
        $featuredProductCategories = Html::tag(
            'div',
            '[featured-product-categories title="Danh mục nổi bật"][/featured-product-categories]'
        );
        $bigBanner = Html::tag(
            'div',
            '[big-banner cover_image="general/home-6.jpeg" show_newsletter_form="yes" number_display_featured_categories="4" title="Bạn đang tìm kiếm sản phẩm?"][/big-banner]'
        );
        $trendingProducts = Html::tag('div', '[trending-products title="Các mặt hàng thịnh hành" limit="20"][/trending-products]');

        $translations = [
            [
                'name' => 'Trang chủ',
                'content' =>
                    $simpleSlider1 .
                    $featuredProductCategories .
                    $themeAds .
                    $popularProducts .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup,
            ],
            [
                'name' => 'Trang chủ 2',
                'content' =>
                    $simpleSlider2 .
                    $themeAds .
                    $popularProducts .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup .
                    $productCategories,
            ],
            [
                'name' => 'Trang chủ 3',
                'content' =>
                    $simpleSlider1 .
                    $popularProducts .
                    $flashSale .
                    $themeAds .
                    $productCategories .
                    $topProductsGroup,
            ],
            [
                'name' => 'Trang chủ 4',
                'content' =>
                    Html::tag('div', '[simple-slider key="home-slider-4" show_newsletter_form="yes"][/simple-slider]') .
                    $popularProducts .
                    $flashSale .
                    $themeAds .
                    $productCategories .
                    $topProductsGroup,
            ],
            [
                'name' => 'Tin tức',
                'content' => Html::tag('p', '---'),
            ],
            [
                'name' => 'Liên hệ',
                'content' => Html::tag('p', '[google-map]502 New Street, Brighton VIC, Australia[/google-map]') .
                    Html::tag('p', '[our-offices][/our-offices]') .
                    Html::tag('p', '[contact-form][/contact-form]'),
            ],
            [
                'name' => 'Về chúng tôi',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Chính sách cookie',
                'content' => Html::tag('h3', 'EU Cookie Consent') .
                    Html::tag(
                        'p',
                        'To use this website we are using Cookies and collecting some data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.'
                    ) .
                    Html::tag('h4', 'Essential Data') .
                    Html::tag(
                        'p',
                        'The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.'
                    ) .
                    Html::tag(
                        'p',
                        '- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.'
                    ) .
                    Html::tag(
                        'p',
                        '- XSRF-Token Cookie: Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.'
                    ),
            ],
            [
                'name' => 'Điều kiện và điều khoản',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Chính sách trả hàng',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Chính sách vận chuyển',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Chính sách bảo mật',
                'content' => Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)) .
                    Html::tag('p', $faker->realText(500)) . Html::tag('p', $faker->realText(500)),
            ],
            [
                'name' => 'Blog danh sách',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
            ],
            [
                'name' => 'Blog Lớn',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
            ],
            [
                'name' => 'Blog Rộng',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
            ],
            [
                'name' => 'Trang chủ 5',
                'content' =>
                    $simpleSlider5 .
                    $featuredProductCategories .
                    $themeAds .
                    $popularProducts .
                    $themeAds2 .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup,
            ],
            [
                'name' => 'Trang chủ 6',
                'content' =>
                    $bigBanner .
                    $trendingProducts .
                    $flashSale .
                    $topProductsGroup,
            ],
            [
                'name' => 'Câu hỏi thường gặp',
                'content' => Html::tag('div', '[faqs][/faqs]'),
            ],
        ];

        foreach ($translations as $index => $item) {
            $item['lang_code'] = 'vi';
            $item['pages_id'] = $index + 1;

            DB::table('pages_translations')->insert($item);
        }
    }
}
