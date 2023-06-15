<?php

namespace Botble\Ecommerce\Widgets;

use Botble\Ecommerce\Enums\OrderStatusEnum;
use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Base\Widgets\Card;
use Botble\Payment\Enums\PaymentStatusEnum;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class RevenueCard extends Card
{
    public function getOptions(): array
    {
        if (! is_plugin_active('payment')) {
            return [];
        }

        $data = app(OrderInterface::class)
            ->getModel()
            ->whereDate('created_at', '>=', $this->startDate)
            ->whereDate('created_at', '<=', $this->endDate)
            ->select([
                DB::raw('SUM(COALESCE(amount, 0)) as revenue'),
            ])
            ->selectRaw('count(id) as total, date_format(created_at, "' . $this->dateFormat . '") as period')
            ->groupBy('period')
            ->pluck('revenue')
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
        if (! is_plugin_active('payment')) {
            return parent::getViewData();
        }

        $revenue = app(OrderInterface::class)
            ->getModel()
            ->select([
                DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
                'payments.status',
            ])
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->whereIn('payments.status', [PaymentStatusEnum::COMPLETED, PaymentStatusEnum::PENDING])
            ->whereDate('payments.created_at', '>=', $this->startDate)
            ->whereDate('payments.created_at', '<=', $this->endDate)
            ->groupBy('payments.status')
            ->first();

        $startDate = clone $this->startDate;
        $endDate = clone $this->endDate;

        $currentPeriod = CarbonPeriod::create($startDate, $endDate);
        $previousPeriod = CarbonPeriod::create($startDate->subDays($currentPeriod->count()), $endDate->subDays($currentPeriod->count()));

        $currentRevenue = app(OrderInterface::class)
            ->getModel()
            ->where('status', OrderStatusEnum::COMPLETED)
            ->whereDate('created_at', '>=', $currentPeriod->getStartDate())
            ->whereDate('created_at', '<=', $currentPeriod->getEndDate())
            ->select([
                DB::raw('SUM(COALESCE(amount, 0)) as revenue'),
            ])
            ->pluck('revenue')
            ->toArray()[0];

        $previousRevenue = app(OrderInterface::class)
            ->getModel()
            ->where('status', OrderStatusEnum::COMPLETED)
            ->whereDate('created_at', '>=', $previousPeriod->getStartDate())
            ->whereDate('created_at', '<=', $previousPeriod->getEndDate())
            ->select([
                DB::raw('SUM(COALESCE(amount, 0)) as revenue'),
            ])
            ->pluck('revenue')
            ->toArray()[0];

        $result = $currentRevenue - $previousRevenue;

        $result > 0 ? $this->chartColor = '#4ade80' : $this->chartColor = '#ff5b5b';

        return array_merge(parent::getViewData(), [
            'content' => view(
                'plugins/ecommerce::reports.widgets.revenue-card',
                compact('revenue', 'result')
            )->render(),
        ]);
    }
}
