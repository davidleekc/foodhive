<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Base\Widgets\Card;
use Carbon\CarbonPeriod;

class NewOrderCard extends Card
{
    public function getOptions(): array
    {
        $data = app(OrderInterface::class)
            ->getModel()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->selectRaw('count(id) as total, date_format(created_at, "' . $this->dateFormat . '") as period')
            ->groupBy('period')
            ->pluck('total')
            ->toArray();

        return [
            'series' => [
                [
                    'data' => $data,
                ],
            ],
        ];
    }

    public function getViewData(): array
    {
        $count = app(OrderInterface::class)
            ->getModel()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->count();

        $startDate = clone $this->startDate;
        $endDate = clone $this->endDate;

        $currentPeriod = CarbonPeriod::create($startDate, $endDate);
        $previousPeriod = CarbonPeriod::create($startDate->subDays($currentPeriod->count()), $endDate->subDays($currentPeriod->count()));

        $currentOrders = app(OrderInterface::class)
            ->getModel()
            ->whereDate('created_at', '>=', $currentPeriod->getStartDate())
            ->whereDate('created_at', '<=', $currentPeriod->getEndDate())
            ->count();

        $previousOrders = app(OrderInterface::class)
            ->getModel()
            ->whereDate('created_at', '>=', $previousPeriod->getStartDate())
            ->whereDate('created_at', '<=', $previousPeriod->getEndDate())
            ->count();

        $result = $currentOrders - $previousOrders;

        $result > 0 ? $this->chartColor = '#4ade80' : $this->chartColor = '#ff5b5b';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/ecommerce::reports.widgets.new-order-card',
                compact('count', 'result')
            )->render(),
        ]);
    }
}
