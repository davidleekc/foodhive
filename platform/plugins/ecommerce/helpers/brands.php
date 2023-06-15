<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\BrandInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

if (! function_exists('get_featured_brands')) {
    function get_featured_brands(int $limit = 8, array $with = ['slugable'], array $withCount = []): Collection|LengthAwarePaginator
    {
        return app(BrandInterface::class)->advancedGet([
            'condition' => [
                'is_featured' => 1,
                'status' => BaseStatusEnum::PUBLISHED,
            ],
            'order_by' => [
                'order' => 'ASC',
                'created_at' => 'DESC',
            ],
            'with' => $with,
            'withCount' => $withCount,
            'take' => $limit,
        ]);
    }
}

if (! function_exists('get_all_brands')) {
    function get_all_brands(array $conditions = [], array $with = ['slugable'], array $withCount = []): Collection
    {
        return app(BrandInterface::class)->advancedGet([
            'condition' => $conditions,
            'order_by' => [
                'order' => 'ASC',
                'created_at' => 'DESC',
            ],
            'with' => $with,
            'withCount' => $withCount,
        ]);
    }
}
