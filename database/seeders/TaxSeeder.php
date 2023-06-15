<?php

namespace Database\Seeders;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\BaseSeeder;
use Botble\Ecommerce\Models\Tax;

class TaxSeeder extends BaseSeeder
{
    public function run(): void
    {
        Tax::truncate();

        Tax::create([
            'title' => 'VAT',
            'percentage' => 10,
            'priority' => 1,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        Tax::create([
            'title' => 'None',
            'percentage' => 0,
            'priority' => 2,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);

        Tax::create([
            'title' => 'Import Tax',
            'percentage' => 15,
            'priority' => 3,
            'status' => BaseStatusEnum::PUBLISHED,
        ]);
    }
}
