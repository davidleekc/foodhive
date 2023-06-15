<?php

namespace Botble\Ecommerce\Repositories\Eloquent;

use Botble\Ecommerce\Repositories\Interfaces\OrderInterface;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository extends RepositoriesAbstract implements OrderInterface
{
    public function getRevenueData(CarbonInterface $startDate, CarbonInterface $endDate, $select = []): Collection
    {
        if (empty($select)) {
            $select = [
                DB::raw('DATE(payments.created_at) AS date'),
                DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
            ];
        }
        $data = $this->model
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->whereDate('payments.created_at', '>=', $startDate)
            ->whereDate('payments.created_at', '<=', $endDate)
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->groupBy('date')
            ->select($select);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function countRevenueByDateRange(CarbonInterface $startDate, CarbonInterface $endDate): float
    {
        $data = $this->model
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->whereDate('payments.created_at', '>=', $startDate)
            ->whereDate('payments.created_at', '<=', $endDate)
            ->where('payments.status', PaymentStatusEnum::COMPLETED);

        return $this
            ->applyBeforeExecuteQuery($data)
            ->sum(DB::raw('COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)'));
    }
}
