<?php

namespace Botble\Shippo;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Models\Setting;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Setting::query()
            ->whereIn('key', [
                'shipping_shippo_status',
                'shipping_shippo_test_key',
                'shipping_shippo_production_key',
                'shipping_shippo_sandbox',
                'shipping_shippo_logging',
                'shipping_shippo_cache_response',
                'shipping_shippo_webhooks',
            ])
            ->delete();
    }
}
