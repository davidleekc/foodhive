<?php

namespace Botble\BottomBarMenu\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Theme;

class BottomBarMenuServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot()
    {
        $this->setNamespace('plugins/bottom-bar-menu')
            ->loadAndPublishViews()
            ->publishAssets();

        Event::listen(RouteMatched::class, function () {
            Theme::asset()
                ->usePath(false)
                ->add(
                    'bottom-bar-menu-css',
                    asset('vendor/core/plugins/bottom-bar-menu/css/menu.css'),
                    [],
                    [],
                    '1.0.0'
                );

            Theme::asset()
                ->container('footer')
                ->usePath(false)
                ->add(
                    'bottom-bar-menu-js',
                    asset('vendor/core/plugins/bottom-bar-menu/js/menu.js'),
                    ['jquery'],
                    [],
                    '1.0.0'
                );
        });

        $this->app->booted(function () {
            add_filter(THEME_FRONT_FOOTER, function ($html) {
                return $html . view('plugins/bottom-bar-menu::menu')->render();
            }, 16);
        });
    }
}
