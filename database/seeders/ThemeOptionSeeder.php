<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Setting\Models\Setting;
use Theme;

class ThemeOptionSeeder extends BaseSeeder
{
    public function run(): void
    {
        $this->uploadFiles('general');

        $theme = Theme::getThemeName();

        Setting::whereIn('key', ['theme', 'admin_favicon', 'admin_logo'])->delete();
        Setting::where('key', 'LIKE', 'theme-' . $theme . '-%')->delete();

        Setting::insertOrIgnore([
            [
                'key' => 'theme',
                'value' => $theme,
            ],
            [
                'key' => 'admin_favicon',
                'value' => 'general/favicon.png',
            ],
            [
                'key' => 'admin_logo',
                'value' => 'general/logo.png',
            ],
            [
                'key' => 'theme-' . $theme . '-site_title',
                'value' => 'Nest - Laravel Multipurpose eCommerce Script',
            ],
            [
                'key' => 'theme-' . $theme . '-seo_description',
                'value' => 'Nest is an attractive Laravel multivendor eCommerce script specially designed for the multipurpose shops like mega store, grocery store, supermarket, organic shop, and online stores selling products like beverages, vegetables, fruits, ice creams, paste, herbs, juice, meat, cold drinks, sausages, cocktails, soft drinks, cookies…',
            ],
            [
                'key' => 'theme-' . $theme . '-copyright',
                'value' => 'Copyright © 2021 Nest all rights reserved. Powered by Botble.',
            ],
            [
                'key' => 'theme-' . $theme . '-favicon',
                'value' => 'general/favicon.png',
            ],
            [
                'key' => 'theme-' . $theme . '-logo',
                'value' => 'general/logo.png',
            ],
            [
                'key' => 'theme-' . $theme . '-seo_og_image',
                'value' => 'general/open-graph-image.png',
            ],
            [
                'key' => 'theme-' . $theme . '-address',
                'value' => '562 Wellington Road, Street 32, San Francisco',
            ],
            [
                'key' => 'theme-' . $theme . '-hotline',
                'value' => '1900 - 888',
            ],
            [
                'key' => 'theme-' . $theme . '-phone',
                'value' => '+01 2222 365 /(+91) 01 2345 6789',
            ],
            [
                'key' => 'theme-' . $theme . '-working_hours',
                'value' => '10:00 - 18:00, Mon - Sat',
            ],
            [
                'key' => 'theme-' . $theme . '-homepage_id',
                'value' => '1',
            ],
            [
                'key' => 'theme-' . $theme . '-blog_page_id',
                'value' => '5',
            ],
            [
                'key' => 'theme-' . $theme . '-cookie_consent_message',
                'value' => 'Your experience on this site will be improved by allowing cookies ',
            ],
            [
                'key' => 'theme-' . $theme . '-cookie_consent_learn_more_url',
                'value' => url('cookie-policy'),
            ],
            [
                'key' => 'theme-' . $theme . '-cookie_consent_learn_more_text',
                'value' => 'Cookie Policy',
            ],
            [
                'key' => 'theme-' . $theme . '-payment_methods',
                'value' => 'general/payment-methods.png',
            ],
            [
                'key' => 'theme-' . $theme . '-number_of_cross_sale_product',
                'value' => 4,
            ],
            [
                'key' => 'theme-' . $theme . '-mobile-header-message',
                'value' => '<span>Grand opening, <strong>up to 15%</strong> off all items. Only <strong>3 days</strong> left</span>',
            ],
            [
                'key' => 'theme-' . $theme . '-blog_page_background',
                'value' => 'general/header-bg.png',
            ],
            [
                'key' => 'theme-' . $theme . '-blog_page_icon',
                'value' => 'general/category-1.png',
            ],
            [
                'key' => 'theme-' . $theme . '-image_in_login_page',
                'value' => 'general/login-1.png',
            ],
            [
                'key' => 'theme-' . $theme . '-number_of_products_per_page',
                'value' => 12,
            ],
            [
                'key' => 'theme-' . $theme . '-preloader_enabled',
                'value' => 'yes',
            ],
            [
                'key' => 'theme-' . $theme . '-preloader_version',
                'value' => 'v2',
            ],
            [
                'key' => 'theme-' . $theme . '-preloader_image',
                'value' => 'general/loading.gif',
            ],
        ]);

        $socialLinks = [
            [
                [
                    'key' => 'social-name',
                    'value' => 'Facebook',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'general/facebook.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://www.facebook.com/',
                ],
            ],
            [
                [
                    'key' => 'social-name',
                    'value' => 'Twitter',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'general/twitter.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://www.twitter.com/',
                ],
            ],
            [
                [
                    'key' => 'social-name',
                    'value' => 'Instagram',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'general/instagram.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://www.instagram.com/',
                ],
            ],
            [
                [
                    'key' => 'social-name',
                    'value' => 'Pinterest',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'general/pinterest.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://www.pinterest.com/',
                ],
            ],
            [
                [
                    'key' => 'social-name',
                    'value' => 'Youtube',
                ],
                [
                    'key' => 'social-icon',
                    'value' => 'general/youtube.png',
                ],
                [
                    'key' => 'social-url',
                    'value' => 'https://www.youtube.com/',
                ],
            ],
        ];

        Setting::insertOrIgnore([
            'key' => 'theme-' . $theme . '-social_links',
            'value' => json_encode($socialLinks),
        ]);

        $headerMessages = [
            [
                [
                    'key' => 'icon',
                    'value' => 'fi-rs-bell',
                ],
                [
                    'key' => 'message',
                    'value' => '<b class="text-success"> Trendy 25</b> silver jewelry, save up 35% off today',
                ],
                [
                    'key' => 'link',
                    'value' => '/products',
                ],
                [
                    'key' => 'link_text',
                    'value' => 'Shop now',
                ],
            ],
            [
                [
                    'key' => 'icon',
                    'value' => 'fi-rs-asterisk',
                ],
                [
                    'key' => 'message',
                    'value' => '<b class="text-danger">Super Value Deals</b> - Save more with coupons',
                ],
                [
                    'key' => 'link',
                    'value' => '/products',
                ],
                [
                    'key' => 'link_text',
                    'value' => null,
                ],
            ],
            [
                [
                    'key' => 'icon',
                    'value' => 'fi-rs-angle-double-right',
                ],
                [
                    'key' => 'message',
                    'value' => 'Get great devices up to 50% off',
                ],
                [
                    'key' => 'link',
                    'value' => '/products',
                ],
                [
                    'key' => 'link_text',
                    'value' => 'View details',
                ],
            ],
        ];

        Setting::insertOrIgnore([
            'key' => 'theme-' . $theme . '-header_messages',
            'value' => json_encode($headerMessages),
        ]);

        $contacts = [
            [
                [
                    'key' => 'name',
                    'value' => 'Head Office',
                ],
                [
                    'key' => 'address',
                    'value' => '205 North Michigan Avenue, Suite 810, Chicago, 60601, USA',
                ],
                [
                    'key' => 'phone',
                    'value' => '(+01) 234 567',
                ],
                [
                    'key' => 'email',
                    'value' => 'office@botble.com',
                ],
            ],
            [
                [
                    'key' => 'name',
                    'value' => 'Our Studio',
                ],
                [
                    'key' => 'address',
                    'value' => '205 North Michigan Avenue, Suite 810, Chicago, 60601, USA',
                ],
                [
                    'key' => 'phone',
                    'value' => '(+01) 234 567',
                ],
                [
                    'key' => 'email',
                    'value' => 'studio@botble.com',
                ],
            ],
            [
                [
                    'key' => 'name',
                    'value' => 'Our Shop',
                ],
                [
                    'key' => 'address',
                    'value' => '205 North Michigan Avenue, Suite 810, Chicago, 60601, USA',
                ],
                [
                    'key' => 'phone',
                    'value' => '(+01) 234 567',
                ],
                [
                    'key' => 'email',
                    'value' => 'shop@botble.com',
                ],
            ],
        ];

        Setting::insertOrIgnore([
            'key' => 'theme-' . $theme . '-contact_info_boxes',
            'value' => json_encode($contacts),
        ]);

        //------------------------------------------ Translation

        Setting::insertOrIgnore([
            [
                'key' => 'theme-' . $theme . '-vi-font_text',
                'value' => 'Roboto Condensed',
            ],
            [
                'key' => 'theme-' . $theme . '-vi-copyright',
                'value' => 'Bản quyền © 2021 Nest tất cả quyền đã được bảo hộ. Phát triển bởi Botble.',
            ],
            [
                'key' => 'theme-' . $theme . '-vi-working_hours',
                'value' => '10:00 - 18:00, Thứ Hai - Thứ Bảy',
            ],
            [
                'key' => 'theme-' . $theme . '-vi-cookie_consent_message',
                'value' => 'Trải nghiệm của bạn trên trang web này sẽ được cải thiện bằng cách cho phép cookie ',
            ],
            [
                'key' => 'theme-' . $theme . '-vi-cookie_consent_learn_more_url',
                'value' => url('cookie-policy'),
            ],
            [
                'key' => 'theme-' . $theme . '-vi-cookie_consent_learn_more_text',
                'value' => 'Chính sách cookie',
            ],
            [
                'key' => 'theme-' . $theme . '-vi-homepage_id',
                'value' => '1',
            ],
            [
                'key' => 'theme-' . $theme . '-vi-blog_page_id',
                'value' => '5',
            ],
        ]);

        $headerMessages = [
            [
                [
                    'key' => 'icon',
                    'value' => 'fi-rs-bell',
                ],
                [
                    'key' => 'message',
                    'value' => '<b class="text-success">Trang sức bạc 25 </b> thời thượng, tiết kiệm đến 35%',
                ],
                [
                    'key' => 'link',
                    'value' => '/products',
                ],
                [
                    'key' => 'link_text',
                    'value' => 'Mua ngay',
                ],
            ],
            [
                [
                    'key' => 'icon',
                    'value' => 'fi-rs-asterisk',
                ],
                [
                    'key' => 'message',
                    'value' => '<b class="text-danger">Ưu đãi siêu giá trị </b> - Tiết kiệm hơn với phiếu thưởng',
                ],
                [
                    'key' => 'link',
                    'value' => null,
                ],
                [
                    'key' => 'link_text',
                    'value' => null,
                ],
            ],
            [
                [
                    'key' => 'icon',
                    'value' => 'fi-rs-angle-double-right',
                ],
                [
                    'key' => 'message',
                    'value' => 'Nhận các sản phẩm tuyệt vời giảm giá tới 50%',
                ],
                [
                    'key' => 'link',
                    'value' => '/products',
                ],
                [
                    'key' => 'link_text',
                    'value' => 'Xem chi tiết',
                ],
            ],
        ];

        Setting::insertOrIgnore([
            'key' => 'theme-' . $theme . '-vi-header_messages',
            'value' => json_encode($headerMessages),
        ]);

        $contacts = [
            [
                [
                    'key' => 'name',
                    'value' => 'Trụ sở chính',
                ],
                [
                    'key' => 'address',
                    'value' => '205 North Michigan Avenue, Suite 810, Chicago, 60601, USA',
                ],
                [
                    'key' => 'phone',
                    'value' => '(+01) 234 567',
                ],
                [
                    'key' => 'email',
                    'value' => 'office@botble.com',
                ],
            ],
            [
                [
                    'key' => 'name',
                    'value' => 'Gian hàng trưng bày',
                ],
                [
                    'key' => 'address',
                    'value' => '205 North Michigan Avenue, Suite 810, Chicago, 60601, USA',
                ],
                [
                    'key' => 'phone',
                    'value' => '(+01) 234 567',
                ],
                [
                    'key' => 'email',
                    'value' => 'studio@botble.com',
                ],
            ],
            [
                [
                    'key' => 'name',
                    'value' => 'Cửa hàng',
                ],
                [
                    'key' => 'address',
                    'value' => '205 North Michigan Avenue, Suite 810, Chicago, 60601, USA',
                ],
                [
                    'key' => 'phone',
                    'value' => '(+01) 234 567',
                ],
                [
                    'key' => 'email',
                    'value' => 'shop@botble.com',
                ],
            ],
        ];

        Setting::insertOrIgnore([
            'key' => 'theme-' . $theme . '-vi-contact_info_boxes',
            'value' => json_encode($contacts),
        ]);
    }
}
