<?php

namespace Botble\Ecommerce\Tables;

use BaseHelper;
use Botble\Ecommerce\Repositories\Interfaces\DiscountInterface;
use Botble\Table\Abstracts\TableAbstract;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class DiscountTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = false;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, DiscountInterface $discountRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $discountRepository;

        if (! Auth::user()->hasPermission('discounts.destroy')) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('detail', function ($item) {
                return view('plugins/ecommerce::discounts.detail', compact('item'))->render();
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('total_used', function ($item) {
                if ($item->type === 'promotion') {
                    return '&mdash;';
                }

                if ($item->quantity === null) {
                    return $item->total_used;
                }

                return $item->total_used . '/' . $item->quantity;
            })
            ->editColumn('start_date', function ($item) {
                return BaseHelper::formatDate($item->start_date);
            })
            ->editColumn('end_date', function ($item) {
                if (! $item->end_date) {
                    return '&mdash;';
                }

                return $item->end_date;
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations(null, 'discounts.destroy', $item);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()->select(['*']);

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
            'detail' => [
                'name' => 'code',
                'title' => trans('plugins/ecommerce::discount.detail'),
                'class' => 'text-start',
            ],
            'total_used' => [
                'title' => trans('plugins/ecommerce::discount.used'),
                'width' => '100px',
            ],
            'start_date' => [
                'title' => trans('plugins/ecommerce::discount.start_date'),
                'class' => 'text-center',
            ],
            'end_date' => [
                'title' => trans('plugins/ecommerce::discount.end_date'),
                'class' => 'text-center',
            ],
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('discounts.create'), 'discounts.create');
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('discounts.deletes'), 'discounts.destroy', parent::bulkActions());
    }

    public function renderTable($data = [], $mergeData = []): View|Factory|Response
    {
        if ($this->query()->count() === 0 &&
            ! $this->request()->wantsJson() &&
            $this->request()->input('filter_table_id') !== $this->getOption('id') && ! $this->request()->ajax()
        ) {
            return view('plugins/ecommerce::discounts.intro');
        }

        return parent::renderTable($data, $mergeData);
    }
}
