<?php

use Botble\Theme\Theme;

return [

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials" and "views"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these events can be overridden by package config.
    |
    */

    'events' => [

        // Before event inherit from package config and the theme that call before,
        // you can use this event to set meta, breadcrumb template or anything
        // you want inheriting.
        'before' => function ($theme) {
            // You can remove this line anytime.
        },

        // Listen on event before render a theme,
        // this event should call to assign some assets,
        // breadcrumb template.
        'beforeRenderTheme' => function (Theme $theme) {
            if (is_plugin_active('ecommerce')) {
                $categories = ProductCategoryHelper::getActiveTreeCategories();

                $theme->partialComposer('header', function ($view) use ($categories) {
                    $view->with('categories', $categories);
                });
            }

            $version = get_cms_version();

            /*import vendors*/
            $theme->asset()->usePath()->add('normalize-css', 'css/vendors/normalize.css');
            $theme->asset()->usePath()->add('bootstrap-css', 'css/vendors/bootstrap.min.css');
            $theme->asset()->usePath()->add('uicons-css', 'css/vendors/uicons-regular-straight.css');

            /*import plugins*/
            if (theme_option('animation_enabled', 'yes') == 'yes') {
                $theme->asset()->usePath()->add('animate-css', 'css/plugins/animate.min.css');
            }

            $theme->asset()->usePath()->add('slick-css', 'css/plugins/slick.css');
            $theme->asset()->usePath()->add('style-css', 'css/style.css', ['jquery-ui-css'], [], $version);

            if (BaseHelper::siteLanguageDirection() == 'rtl') {
                $theme->asset()->usePath()->add('rtl', 'css/rtl.css', [], [], $version);
            }

            $theme->asset()->container('footer')->usePath()->add('jquery', 'js/vendor/jquery-3.6.0.min.js');
            $theme->asset()->container('footer')->usePath()->add('bootstrap-js', 'js/vendor/bootstrap.bundle.min.js');
            $theme->asset()->container('footer')->usePath()->add('slick-js', 'js/plugins/slick.js');
            $theme->asset()->container('footer')->usePath()
                ->add('jquery.syotimer-js', 'js/plugins/jquery.syotimer.min.js');

            if (theme_option('animation_enabled', 'yes') == 'yes') {
                $theme->asset()->container('footer')->usePath()->add('wow-js', 'js/plugins/wow.js');
            }

            $theme->asset()->container('footer')->usePath()->add('waypoints-js', 'js/plugins/waypoints.js');
            $theme->asset()->container('footer')->usePath()
                ->add('jquery.countdown-js', 'js/plugins/jquery.countdown.min.js');
            $theme->asset()->container('footer')->usePath()->add('scrollup-js', 'js/plugins/scrollup.js');
            $theme->asset()->container('footer')->usePath()
                ->add('jquery.vticker-js', 'js/plugins/jquery.vticker-min.js');
            $theme->asset()->container('footer')->usePath()
                ->add('main', 'js/main.js', ['jquery.theia.sticky-js', 'jquery.elevatezoom-js'], [], $version);
            $theme->asset()->container('footer')->usePath()->add('backend', 'js/backend.js', [], [], $version);
            $theme->asset()->container('footer')->usePath()->add('components-js', 'js/components.js', [], [], $version);

            if (function_exists('shortcode')) {
                $theme->composer(['page', 'post', 'ecommerce.product'], function (\Botble\Shortcode\View\View $view) {
                    $view->withShortcodes();
                });
            }
        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => [

            'default' => function ($theme) {
                // $theme->asset()->usePath()->add('ipad', 'css/layouts/ipad.css');
            },
        ],
    ],
];
