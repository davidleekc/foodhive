<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;
use Botble\Marketplace\Repositories\Interfaces\WithdrawalInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class VendorWithdrawalTable extends TableAbstract
{
    protected $hasCheckbox = false;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, WithdrawalInterface $revenueRepository)
    {
        $this->repository = $revenueRepository;
        parent::__construct($table, $urlGenerator);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('fee', function ($item) {
                return format_price($item->fee);
            })
            ->editColumn('amount', function ($item) {
                return format_price($item->amount);
            })
            ->editColumn('status', function ($item) {
                return BaseHelper::clean($item->status->toHtml());
            })
            ->addColumn('operations', function ($item) {
                return view('plugins/marketplace::withdrawals.tables.actions', compact('item'))->render();
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'fee',
                'amount',
                'status',
                'currency',
                'created_at',
            ])
            ->where('customer_id', auth('customer')->id());

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'amount' => [
                'title' => trans('plugins/ecommerce::order.amount'),
            ],
            'fee' => [
                'title' => trans('plugins/ecommerce::shipping.fee'),
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
            ],
        ];
    }

    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('marketplace.vendor.withdrawals.create'));
    }
}
