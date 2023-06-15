<?php

namespace Database\Seeders;

use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Enums\ShippingRuleTypeEnum;
use Botble\Ecommerce\Models\Shipping;
use Botble\Ecommerce\Models\ShippingRule;
use Botble\Ecommerce\Models\ShippingRuleItem;

class ShippingSeeder extends BaseSeeder
{
    public function run(): void
    {
        Shipping::truncate();
        ShippingRule::truncate();
        ShippingRuleItem::truncate();

        Shipping::create(['title' => 'All']);

        ShippingRule::create([
            'name' => 'Free delivery',
            'shipping_id' => 1,
            'type' => ShippingRuleTypeEnum::BASED_ON_PRICE,
            'from' => 0,
            'to' => null,
            'price' => 0,
        ]);
    }
}
