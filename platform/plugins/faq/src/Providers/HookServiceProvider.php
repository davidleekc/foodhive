<?php

namespace Botble\Faq\Providers;

use Assets;
use BaseHelper;
use Botble\Faq\Contracts\Faq as FaqContract;
use Botble\Faq\FaqCollection;
use Botble\Faq\FaqItem;
use Html;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use MetaBox;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action(BASE_ACTION_META_BOXES, function ($context, $object): void {
            if (! $object || $context != 'advanced') {
                return;
            }

            if (! in_array(get_class($object), config('plugins.faq.general.schema_supported', []))) {
                return;
            }

            if (! setting('enable_faq_schema', 0)) {
                return;
            }

            Assets::addStylesDirectly(['vendor/core/plugins/faq/css/faq.css'])
                ->addScriptsDirectly(['vendor/core/plugins/faq/js/faq.js']);

            MetaBox::addMetaBox(
                'faq_schema_config_wrapper',
                trans('plugins/faq::faq.faq_schema_config', [
                    'link' => Html::link(
                        'https://developers.google.com/search/docs/data-types/faqpage',
                        trans('plugins/faq::faq.learn_more'),
                        ['target' => '_blank']
                    ),
                ]),
                function () {
                    $value = [];

                    $args = func_get_args();
                    if ($args[0] && $args[0]->id) {
                        $value = MetaBox::getMetaData($args[0], 'faq_schema_config', true);
                    }

                    $hasValue = ! empty($value);

                    $value = json_encode((array)$value);

                    return view('plugins/faq::schema-config-box', compact('value', 'hasValue'))->render();
                },
                get_class($object),
                $context
            );
        }, 39, 2);

        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $object): void {
            add_filter(THEME_FRONT_HEADER, function ($html) use ($object): ?string {
                if (! in_array(get_class($object), config('plugins.faq.general.schema_supported', []))) {
                    return $html;
                }

                if (! setting('enable_faq_schema', 0)) {
                    return $html;
                }

                $value = MetaBox::getMetaData($object, 'faq_schema_config', true);

                if (! $value || ! is_array($value)) {
                    return $html;
                }

                if (empty($value)) {
                    return $html;
                }

                foreach ($value as $key => $item) {
                    if (! $item[0]['value'] && ! $item[1]['value']) {
                        Arr::forget($value, $key);
                    }
                }

                $schemaItems = new FaqCollection();

                foreach ($value as $item) {
                    $schemaItems->push(
                        new FaqItem(BaseHelper::clean($item[0]['value']), BaseHelper::clean($item[1]['value']))
                    );
                }

                app(FaqContract::class)->registerSchema($schemaItems);

                return $html;
            }, 39);
        }, 39, 2);

        add_filter(BASE_FILTER_AFTER_SETTING_CONTENT, [$this, 'addSettings'], 59);
    }

    public function addSettings(?string $data = null): string
    {
        return $data . view('plugins/faq::settings')->render();
    }
}
