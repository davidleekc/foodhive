<?php

namespace Botble\Marketplace\Tables;

use BaseHelper;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnInterface;
use Botble\Ecommerce\Repositories\Interfaces\OrderReturnItemInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use MarketplaceHelper;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class OrderReturnTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        OrderReturnInterface $orderReturnRepository,
        protected OrderReturnItemInterface $orderReturnItemRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $orderReturnRepository;
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('return_status', function ($item) {
                return BaseHelper::clean($item->return_status->toHtml());
            })
            ->editColumn('reason', function ($item) {
                return BaseHelper::clean($item->reason->toHtml());
            })
            ->editColumn('order_id', function ($item) {
                return BaseHelper::clean($item->order->code);
            })
            ->editColumn('user_id', function ($item) {
                if (! $item->customer->name) {
                    return '&mdash;';
                }

                return BaseHelper::clean($item->customer->name);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            });

        $data = $data
            ->filter(function ($query) {
                $keyword = $this->request->input('search.value');
                if ($keyword) {
                    return $query
                        ->whereHas('items', function ($subQuery) use ($keyword) {
                            return $subQuery->where('product_name', 'LIKE', '%' . $keyword . '%');
                        })->orWhereHas('customer', function ($subQuery) use ($keyword) {
                            return $subQuery->where('name', 'LIKE', '%' . $keyword . '%');
                        });
                }

                return $query;
            });

        $data = $data
            ->addColumn('operations', function ($item) {
                return view(MarketplaceHelper::viewPath('dashboard.table.actions'), [
                    'edit' => 'marketplace.vendor.order-returns.edit',
                    'delete' => 'marketplace.vendor.order-returns.destroy',
                    'item' => $item,
                ])->render();
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'order_id',
                'user_id',
                'reason',
                'order_status',
                'return_status',
                'created_at',
            ])
            ->with(['customer', 'order', 'items'])
            ->withCount('items')
            ->where('store_id', auth('customer')->user()->store->id)
            ->orderBy('id', 'desc');

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
                'title' => trans('plugins/ecommerce::order.order_id'),
                'class' => 'text-start',
            ],
            'user_id' => [
                'title' => trans('plugins/ecommerce::order.customer_label'),
                'class' => 'text-start',
            ],
            'items_count' => [
                'title' => trans('plugins/ecommerce::order.order_return_items_count'),
                'class' => 'text-center',
            ],
            'return_status' => [
                'title' => trans('core/base::tables.status'),
                'class' => 'text-center',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-start',
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
}
