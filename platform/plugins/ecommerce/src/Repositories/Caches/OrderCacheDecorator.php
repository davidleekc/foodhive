<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;

class OrderCacheDecorator extends CacheAbstractDecorator implements OrderInterface
{
    public function getRevenueData(CarbonInterface $startDate, CarbonInterface $endDate, array $select = []): Collection
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function countRevenueByDateRange(CarbonInterface $startDate, CarbonInterface $endDate): float
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
