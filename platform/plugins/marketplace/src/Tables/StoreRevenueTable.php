<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;
use Botble\Marketplace\Repositories\Interfaces\RevenueInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\DataTables;

class StoreRevenueTable extends TableAbstract
{
    protected string $type = self::TABLE_TYPE_SIMPLE;

    protected int $defaultSortColumn = 0;

    protected $view = 'core/table::simple-table';

    protected $hasCheckbox = false;

    protected $hasOperations = false;

    protected $repository;

    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        RevenueInterface $revenueRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $revenueRepository;
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('amount', function ($item) {
                return Html::tag('span', ($item->sub_amount < 0 ? '-' : '') . format_price($item->amount), ['class' => 'text-success']);
            })
            ->editColumn('sub_amount', function ($item) {
                return ($item->sub_amount < 0 ? '-' : '') . format_price($item->sub_amount);
            })
            ->editColumn('fee', function ($item) {
                return Html::tag('span', ($item->sub_amount < 0 ? '-' : '') . format_price($item->fee), ['class' => 'text-danger']);
            })
            ->editColumn('order_id', function ($item) {
                if (! $item->order->id) {
                    return $item->description;
                }

                $url = Route::currentRouteName() == 'marketplace.vendor.statements.index' ? route('marketplace.vendor.orders.edit', $item->order->id) : route('orders.edit', $item->order->id);

                return Html::link($url, $item->order->code, ['target' => '_blank']);
            })
            ->editColumn('type', function ($item) {
                return $item->type->toHtml();
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'sub_amount',
                'fee',
                'amount',
                'order_id',
                'created_at',
                'type',
                'description',
            ])
            ->with(['order'])
            ->where('customer_id', request()->route()->parameter('id'))
            ->latest();

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-start',
            ],
            'order_id' => [
                'title' => trans('plugins/ecommerce::order.description'),
                'class' => 'text-start',
            ],
            'fee' => [
                'title' => trans('plugins/ecommerce::shipping.fee'),
                'class' => 'text-start',
            ],
            'sub_amount' => [
                'title' => trans('plugins/ecommerce::order.sub_amount'),
                'class' => 'text-start',
            ],
            'amount' => [
                'title' => trans('plugins/ecommerce::order.amount'),
                'class' => 'text-start',
            ],
            'type' => [
                'title' => trans('plugins/marketplace::revenue.forms.type'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'class' => 'text-start',
                'width' => '100px',
            ],
        ];
    }

    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction() . '$("[data-bs-toggle=tooltip]").tooltip({placement: "top", boundary: "window"});';
    }
}
