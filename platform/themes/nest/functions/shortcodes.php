<?php

use Botble\Ads\Repositories\Interfaces\AdsInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Botble\Theme\Supports\ThemeSupport;

app()->booted(function () {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('simple-slider')) {
        add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
            return Theme::getThemeNamespace() . '::partials.shortcodes.sliders.main';
        }, 120);
    }

    if (is_plugin_active('ecommerce')) {
        add_shortcode(
            'featured-product-categories',
            __('Featured Product Categories'),
            __('Featured Product Categories'),
            function ($shortcode) {
                return Theme::partial('shortcodes.ecommerce.featured-product-categories', compact('shortcode'));
            }
        );

        shortcode()->setAdminConfig('featured-product-categories', function ($attributes) {
            return Theme::partial('shortcodes.ecommerce.featured-product-categories-admin-config', compact('attributes'));
        });

        add_shortcode('flash-sale', __('Flash sale'), __('Flash sale'), function ($shortcode) {
            $flashSalePopup = null;
            if ($shortcode->flash_sale_popup_id) {
                $flashSalePopup = app(FlashSaleInterface::class)
                    ->getModel()
                    ->where(['id' => $shortcode->flash_sale_popup_id])
                    ->notExpired()
                    ->with(['products'])
                    ->first();
            }

            $flashSaleIds = [];
            for ($i = 1; $i <= 4; $i++) {
                if ($shortcode->{'flash_sale_' . $i}) {
                    $flashSaleIds[] = $shortcode->{'flash_sale_' . $i};
                }
            }

            $flashSales = app(FlashSaleInterface::class)
                ->getModel()
                ->notExpired()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->with([
                    'products' => function ($query) {
                        $with = ['slugable', 'productCollections'];
                        if (is_plugin_active('marketplace')) {
                            $with = array_merge($with, ['store', 'store.slugable']);
                        }

                        $reviewParams = EcommerceHelper::withReviewsParams();

                        if (EcommerceHelper::isReviewEnabled()) {
                            $query->withAvg($reviewParams['withAvg'][0], $reviewParams['withAvg'][1]);
                        }

                        return $query
                            ->where('status', BaseStatusEnum::PUBLISHED)
                            ->withCount($reviewParams['withCount'])
                            ->with($with);
                    },
                    'metadata',
                ])
                ->whereIn('id', $flashSaleIds)
                ->get();

            return Theme::partial('shortcodes.flash-sale.default', compact('shortcode', 'flashSales', 'flashSalePopup'));
        });

        shortcode()->setAdminConfig('flash-sale', function ($attributes) {
            $flashSales = app(FlashSaleInterface::class)
                ->getModel()
                ->notExpired()
                ->pluck('name', 'id')
                ->toArray();

            $flashSales = ['' => __('None')] + $flashSales;

            return Theme::partial('shortcodes.flash-sale.default-admin-config', compact('attributes', 'flashSales'));
        });

        add_shortcode('best-flash-sale', __('Best flash sale'), __('Best flash sale'), function ($shortcode) {
            $flashSalePopup = null;
            if ($shortcode->flash_sale_id) {
                $flashSalePopup = app(FlashSaleInterface::class)
                    ->getModel()
                    ->where(['id' => $shortcode->flash_sale_id])
                    ->notExpired()
                    ->where('status', BaseStatusEnum::PUBLISHED)
                    ->with([
                        'products' => function ($query) {
                            $reviewParams = EcommerceHelper::withReviewsParams();

                            if (EcommerceHelper::isReviewEnabled()) {
                                $query->withAvg($reviewParams['withAvg'][0], $reviewParams['withAvg'][1]);
                            }

                            return $query
                                ->where('status', BaseStatusEnum::PUBLISHED)
                                ->withCount($reviewParams['withCount']);
                        },
                        'products.productLabels',
                        'products.categories',
                        'products.categories.slugable',
                        'products.slugable',
                        'products.productCollections',
                        'metadata',
                    ])
                    ->first();
            }
            if (! $flashSalePopup || ! $flashSalePopup->products->count()) {
                return null;
            }

            return Theme::partial('shortcodes.flash-sale.best', compact('shortcode', 'flashSalePopup'));
        });

        shortcode()->setAdminConfig('best-flash-sale', function ($attributes) {
            $flashSales = app(FlashSaleInterface::class)
                ->getModel()
                ->notExpired()
                ->pluck('name', 'id')
                ->toArray();

            $flashSales = ['' => __('None')] + $flashSales;

            return Theme::partial('shortcodes.flash-sale.best-admin-config', compact('attributes', 'flashSales'));
        });

        add_shortcode(
            'product-collections',
            __('Product Collections'),
            __('Product Collections'),
            function ($shortcode) {
                $data = get_product_collections(
                    ['status' => BaseStatusEnum::PUBLISHED],
                    [],
                    ['id', 'name', 'slug']
                );

                $productCollections = [];

                foreach ($data as $collection) {
                    $productCollections[] = [
                        'id' => $collection->id,
                        'name' => $collection->name,
                        'slug' => $collection->slug,
                    ];
                }

                return Theme::partial('shortcodes.ecommerce.product-collections', [
                    'title' => $shortcode->title,
                    'productCollections' => $productCollections,
                ]);
            }
        );

        shortcode()->setAdminConfig('product-collections', function ($attributes) {
            return Theme::partial('shortcodes.ecommerce.product-collections-admin-config', compact('attributes'));
        });

        add_shortcode(
            'product-category-products',
            __('Product category products'),
            __('Product category products'),
            function ($shortcode) {
                $category = app(ProductCategoryInterface::class)->getFirstBy([
                    'status' => BaseStatusEnum::PUBLISHED,
                    'id' => $shortcode->category_id,
                ], ['*'], [
                    'activeChildren' => function ($query) {
                        $query->limit(3);
                    },
                ]);

                if (! $category) {
                    return null;
                }

                return Theme::partial('shortcodes.ecommerce.product-category-products', compact('category'));
            }
        );

        shortcode()->setAdminConfig('product-category-products', function ($attributes) {
            $categories = app(ProductCategoryInterface::class)->pluck(
                'name',
                'id',
                ['status' => BaseStatusEnum::PUBLISHED]
            );

            return Theme::partial(
                'shortcodes.ecommerce.product-category-products-admin-config',
                compact('categories', 'attributes')
            );
        });

        add_shortcode('featured-brands', __('Featured Brands'), __('Featured Brands'), function ($shortcode) {
            return Theme::partial('shortcodes.ecommerce.featured-brands', compact('shortcode'));
        });

        shortcode()->setAdminConfig('featured-brands', function ($attributes) {
            return Theme::partial('shortcodes.ecommerce.featured-brands-admin-config', compact('attributes'));
        });

        add_shortcode('product-categories', __('Product Categories'), __('Product Categories'), function ($shortcode) {
            return Theme::partial('shortcodes.ecommerce.product-categories', compact('shortcode'));
        });

        shortcode()->setAdminConfig('product-categories', function ($attributes) {
            $categoryIds = array_filter(explode(',', Arr::get($attributes, 'categories')));

            return Theme::partial('shortcodes.ecommerce.product-categories-admin-config', compact('attributes', 'categoryIds'));
        });

        add_shortcode('top-products-group', __('Top Products Group'), __('Top Products Group'), function ($shortcode) {
            return Theme::partial('shortcodes.ecommerce.top-products-group', compact('shortcode'));
        });

        shortcode()->setAdminConfig('top-products-group', function ($attributes) {
            $tabs = array_filter(explode(',', Arr::get($attributes, 'tabs')));

            if (empty($tabs)) {
                $tabs = ['top-selling', 'trending-products', 'recent-added', 'top-rated'];
            }

            return Theme::partial('shortcodes.ecommerce.top-products-group-admin-config', compact('tabs', 'attributes'));
        });

        add_shortcode('popular-products', __('Popular Products'), __('Popular Products'), function ($shortcode) {
            return Theme::partial('shortcodes.ecommerce.popular-products', compact('shortcode'));
        });

        shortcode()->setAdminConfig('popular-products', function ($attributes) {
            return Theme::partial('shortcodes.ecommerce.popular-products-admin-config', compact('attributes'));
        });

        add_shortcode('trending-products', __('Trending products'), __('Trending products'), function ($shortcode) {
            return Theme::partial('shortcodes.ecommerce.trending-products', compact('shortcode'));
        });

        shortcode()->setAdminConfig('trending-products', function ($attributes) {
            return Theme::partial('shortcodes.ecommerce.trending-products-admin-config', compact('attributes'));
        });
    }

    if (is_plugin_active('ads')) {
        add_shortcode('theme-ads', __('Theme ads'), __('Theme ads'), function ($shortcode) {
            $keys = get_ads_keys_from_shortcode($shortcode);

            return display_ads($keys, $shortcode->style);
        });

        shortcode()->setAdminConfig('theme-ads', function ($attributes) {
            $ads = app(AdsInterface::class)->getModel()
                ->where('status', BaseStatusEnum::PUBLISHED)
                ->notExpired()
                ->get();

            return Theme::partial('shortcodes.ads.config-in-admin', compact('ads', 'attributes'));
        });

        AdsManager::load();

        function display_ad(BaseModel|string $ads, string $class = '', $loop = null): ?string
        {
            if (! ($ads instanceof BaseModel)) {
                $ads = AdsManager::getData()
                    ->where('key', $ads)
                    ->first();
            }

            if (! $ads || ! $ads->image) {
                return null;
            }

            if ($ads->location &&
                $ads->location != 'not_set' &&
                view()->exists(Theme::getThemeNamespace() . '::partials.shortcodes.ads.' . $ads->location)) {
                return Theme::partial('shortcodes.ads.' . $ads->location, compact('ads', 'class', 'loop'));
            }

            return Theme::partial('shortcodes.ads.item', compact('ads', 'class', 'loop'));
        }

        function get_ads_keys_from_shortcode($shortcode): array
        {
            $keys = collect($shortcode->toArray())
                ->sortKeys()
                ->filter(function ($value, $key) use ($shortcode) {
                    return Str::startsWith($key, 'ads_') ||
                        ($shortcode->name == 'theme-ads' && Str::startsWith($key, 'key_'));
                });

            return array_filter($keys->toArray() + [$shortcode->ads]);
        }

        function display_ads(array $keys, ?string $style = ''): string
        {
            $keys = collect($keys);

            return Theme::partial('shortcodes.ads.items', compact('keys', 'style'));
        }

        if (is_plugin_active('simple-slider')) {
            add_filter(SHORTCODE_REGISTER_CONTENT_IN_ADMIN, function ($data, $key, $attributes) {
                if ($key != 'simple-slider') {
                    return $data;
                }

                if (is_plugin_active('ads')) {
                    $ads = app(AdsInterface::class)->getModel()
                        ->where('status', BaseStatusEnum::PUBLISHED)
                        ->notExpired()
                        ->get();
                    $data .= Theme::partial('shortcodes.ads.config-in-admin', compact('ads', 'attributes') + ['total' => 2]);
                }

                if (is_plugin_active('newsletter')) {
                    $data .= Theme::partial('shortcodes.sliders.newsletter-form-option', compact('attributes'));
                }

                return $data . Theme::partial('shortcodes.sliders.config-in-admin', compact('ads', 'attributes'));
            }, 50, 3);
        }
    }

    if (is_plugin_active('blog')) {
        add_shortcode('featured-news', __('Featured News'), __('Featured News'), function ($shortcode) {
            return Theme::partial('shortcodes.featured-news', ['title' => $shortcode->title]);
        });

        shortcode()->setAdminConfig('featured-news', function ($attributes) {
            return Theme::partial('shortcodes.featured-news-admin-config', compact('attributes'));
        });
    }

    if (is_plugin_active('contact')) {
        add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
            return Theme::getThemeNamespace() . '::partials.shortcodes.contact-form';
        }, 120);
    }

    add_shortcode('our-offices', __('Our offices'), __('Our offices'), function () {
        return Theme::partial('shortcodes.our-offices');
    });

    shortcode()->setAdminConfig('our-offices', function ($attributes) {
        return Theme::partial('shortcodes.our-offices-admin-config', compact('attributes'));
    });

    add_shortcode('big-banner', __('Big banner'), __('Big banner'), function ($shortcode) {
        return Theme::partial('shortcodes.big-banner', compact('shortcode'));
    });

    shortcode()->setAdminConfig('big-banner', function ($attributes) {
        return Theme::partial('shortcodes.big-banner-admin-config', compact('attributes'));
    });

    if (is_plugin_active('faq')) {
        add_shortcode('faqs', __('FAQs'), __('List of FAQs'), function ($shortcode) {
            $params = [
                'condition' => [
                    'status' => BaseStatusEnum::PUBLISHED,
                ],
                'with' => [
                    'faqs' => function ($query) {
                        $query->where('status', BaseStatusEnum::PUBLISHED);
                    },
                ],
                'order_by' => [
                    'faq_categories.order' => 'ASC',
                    'faq_categories.created_at' => 'DESC',
                ],
            ];

            if ($shortcode->category_id) {
                $params['condition']['id'] = $shortcode->category_id;
            }

            $categories = app(FaqCategoryInterface::class)->advancedGet($params);

            return Theme::partial('shortcodes.faqs', compact('categories'));
        });

        shortcode()->setAdminConfig('faqs', function ($attributes) {
            $categories = app(FaqCategoryInterface::class)->pluck('name', 'id', ['status' => BaseStatusEnum::PUBLISHED]);

            return Theme::partial('shortcodes.faqs-admin-config', compact('attributes', 'categories'));
        });
    }
});
