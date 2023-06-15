<?php

namespace Botble\Ecommerce\Tables;

use BaseHelper;
use Botble\Ecommerce\Repositories\Interfaces\ShippingRuleItemInterface;
use Botble\Table\Abstracts\TableAbstract;
use EcommerceHelper;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ShippingRuleItemTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    protected array $countries;

    protected bool $isExporting;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ShippingRuleItemInterface $repository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $repository;

        if (! Auth::user()->hasAnyPermission(['ecommerce.shipping-rule-items.bulk-import.edit'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }

        $this->countries = EcommerceHelper::getAvailableCountries();
        $this->isExporting = in_array($this->request()->input('action'), ['csv', 'excel']);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('shipping_rule_id', function ($item) {
                return $item->shippingRule->name;
            })
            ->editColumn('country', function ($item) {
                return Arr::get(
                    $this->countries,
                    $item->shippingRule->shipping->country
                ) ?: $item->shippingRule->shipping->country;
            });
        if ($this->isExporting) {
            $data = $data->editColumn('is_enabled', function ($item) {
                if ($item->is_enabled) {
                    return trans('core/base::base.yes');
                }

                return trans('core/base::base.no');
            });
        } else {
            $data = $data->editColumn('country', function ($item) {
                return Arr::get(
                    $this->countries,
                    $item->shippingRule->shipping->country
                ) ?: $item->shippingRule->shipping->country;
            })
                ->editColumn('state', function ($item) {
                    return $item->state_name;
                })
                ->editColumn('city', function ($item) {
                    return $item->city_name;
                })
                ->editColumn('adjustment_price', function ($item) {
                    return ($item->adjustment_price < 0 ? '-' : '') .
                        format_price($item->adjustment_price) .
                        Html::tag(
                            'small',
                            '(' . format_price(max($item->adjustment_price + $item->shippingRule->price, 0)) . ')',
                            ['class' => 'text-info ms-1']
                        );
                })
                ->editColumn('is_enabled', function ($item) {
                    if ($item->is_enabled) {
                        return Html::tag('span', trans('core/base::base.yes'), ['class' => 'text-primary']);
                    }

                    return Html::tag('span', trans('core/base::base.no'), ['class' => 'text-secondary']);
                });
        }

        $data = $data->editColumn('created_at', function ($item) {
            return BaseHelper::formatDate($item->created_at);
        })
            ->addColumn('operations', function ($item) {
                return $this->getOperations(
                    'ecommerce.shipping-rule-items.edit',
                    'ecommerce.shipping-rule-items.destroy',
                    $item
                );
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->with(['shippingRule', 'shippingRule.shipping'])
            ->select([
                'id',
                'shipping_rule_id',
                'country',
                'state',
                'city',
                'adjustment_price',
                'is_enabled',
                'zip_code',
                'created_at',
            ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20',
                'class' => 'text-start',
            ],
            'shipping_rule_id' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.tables.shipping_rule'),
                'class' => 'text-center',
            ],
            'country' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.tables.country'),
                'class' => 'text-center',
            ],
            'state' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.tables.state'),
                'class' => 'text-center',
            ],
            'city' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.tables.city'),
                'class' => 'text-center',
            ],
            'zip_code' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.tables.zip_code'),
                'class' => 'text-center',
            ],
            'adjustment_price' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.tables.adjustment_price'),
                'class' => 'text-center',
            ],
            'is_enabled' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.tables.is_enabled'),
                'width' => '40',
                'class' => 'text-center',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '80',
                'class' => 'text-start',
            ],
        ];
    }

    public function buttons(): array
    {
        $buttons = $this->addCreateButton(route('ecommerce.shipping-rule-items.create'), 'shipping_methods.index');

        if (Auth::user()->hasPermission('ecommerce.shipping-rule-items.bulk-import')) {
            $buttons['import'] = [
                'link' => route('ecommerce.shipping-rule-items.bulk-import.index'),
                'text' => '<i class="fas fa-file-import"></i> ' . trans('plugins/ecommerce::bulk-import.tables.import'),
            ];
        }

        return $buttons;
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(
            route('ecommerce.shipping-rule-items.deletes'),
            'shipping_methods.index',
            parent::bulkActions()
        );
    }

    public function getBulkChanges(): array
    {
        return [
            'adjustment_price' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.forms.adjustment_price'),
                'type' => 'number',
                'validate' => 'required|numeric',
            ],
            'is_enabled' => [
                'title' => trans('plugins/ecommerce::shipping.rule.item.forms.is_enabled'),
                'type' => 'select',
                'choices' => [
                    '1' => trans('core/base::base.yes'),
                    '0' => trans('core/base::base.no'),
                ],
                'validate' => 'required|in:0,1',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }

    public function getDefaultButtons(): array
    {
        $buttons = parent::getDefaultButtons();

        return array_merge($buttons, ['export']);
    }
}
