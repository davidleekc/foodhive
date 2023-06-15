<?php

namespace Botble\Ecommerce\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Table\Abstracts\TableAbstract;
use Carbon\Carbon;
use EcommerceHelper;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\Relation as EloquentRelation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

class ProductTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    public function __construct(DataTables $table, UrlGenerator $urlGenerator, ProductInterface $productRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $productRepository;

        if (! Auth::user()->hasAnyPermission(['products.edit', 'products.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                $productType = null;

                if (EcommerceHelper::isEnabledSupportDigitalProducts()) {
                    $productType = Html::tag('small', ' &mdash; ' . $item->product_type->label())->toHtml();
                }

                if (! Auth::user()->hasPermission('products.edit')) {
                    return BaseHelper::clean($item->name) . $productType;
                }

                return Html::link(route('products.edit', $item->id), BaseHelper::clean($item->name)) . $productType;
            })
            ->editColumn('image', function ($item) {
                if ($this->request()->input('action') == 'csv') {
                    return RvMedia::getImageUrl($item->image, null, false, RvMedia::getDefaultImage());
                }

                if ($this->request()->input('action') == 'excel') {
                    return RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage());
                }

                return $this->displayThumbnail($item->image);
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('price', function ($item) {
                return $item->price_in_table;
            })
            ->editColumn('quantity', function ($item) {
                return $item->with_storehouse_management ? $item->quantity : '&#8734;';
            })
            ->editColumn('sku', function ($item) {
                return BaseHelper::clean($item->sku ?: '&mdash;');
            })
            ->editColumn('order', function ($item) {
                return view('plugins/ecommerce::products.partials.sort-order', compact('item'))->render();
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return BaseHelper::clean($item->status->toHtml());
            })
            ->editColumn('stock_status', function ($item) {
                return BaseHelper::clean($item->stock_status_html);
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('products.edit', 'products.destroy', $item);
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()
            ->select([
                'id',
                'name',
                'order',
                'created_at',
                'status',
                'sku',
                'images',
                'price',
                'sale_price',
                'sale_type',
                'start_date',
                'end_date',
                'quantity',
                'with_storehouse_management',
                'stock_status',
                'product_type',
            ])
            ->where('is_variation', 0);

        return $this->applyScopes($query);
    }

    public function htmlDrawCallbackFunction(): ?string
    {
        return parent::htmlDrawCallbackFunction() . '$(".editable").editable({mode: "inline"});';
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'image' => [
                'name' => 'images',
                'title' => trans('plugins/ecommerce::products.image'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'price' => [
                'title' => trans('plugins/ecommerce::products.price'),
                'class' => 'text-start',
            ],
            'stock_status' => [
                'title' => trans('plugins/ecommerce::products.stock_status'),
                'class' => 'text-center',
            ],
            'quantity' => [
                'title' => trans('plugins/ecommerce::products.quantity'),
                'class' => 'text-start',
            ],
            'sku' => [
                'title' => trans('plugins/ecommerce::products.sku'),
                'class' => 'text-start',
            ],
            'order' => [
                'title' => trans('core/base::tables.order'),
                'width' => '50px',
                'class' => 'text-center',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-center',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-center',
            ],
        ];
    }

    public function buttons(): array
    {
        $buttons = [];
        if (EcommerceHelper::isEnabledSupportDigitalProducts() && Auth::user()->hasPermission('products.create')) {
            $buttons['create'] = [
                'extend' => 'collection',
                'text' => view('core/table::partials.create')->render(),
                'buttons' => [
                    [
                        'className' => 'action-item',
                        'text' => ProductTypeEnum::PHYSICAL()->toIcon() . ' ' . Html::tag('span', ProductTypeEnum::PHYSICAL()->label(), [
                            'data-action' => 'physical-product',
                            'data-href' => route('products.create'),
                            'class' => 'ms-1',
                        ])->toHtml(),
                    ],
                    [
                        'className' => 'action-item',
                        'text' => ProductTypeEnum::DIGITAL()->toIcon() . ' ' . Html::tag('span', ProductTypeEnum::DIGITAL()->label(), [
                            'data-action' => 'digital-product',
                            'data-href' => route('products.create', ['product_type' => 'digital']),
                            'class' => 'ms-1',
                        ])->toHtml(),
                    ],
                ],
            ];
        } else {
            $buttons = $this->addCreateButton(route('products.create'), 'products.create');
        }

        if (Auth::user()->hasPermission('ecommerce.bulk-import.index')) {
            $buttons['import'] = [
                'link' => route('ecommerce.bulk-import.index'),
                'text' => '<i class="fas fa-file-import"></i> ' . trans('plugins/ecommerce::bulk-import.tables.import'),
            ];
        }

        if (Auth::user()->hasPermission('ecommerce.export.products.index')) {
            $buttons['export'] = [
                'link' => route('ecommerce.export.products.index'),
                'text' => '<i class="fas fa-file-export"></i> ' . trans('plugins/ecommerce::export.export'),
            ];
        }

        return $buttons;
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('products.deletes'), 'products.destroy', parent::bulkActions());
    }

    public function renderTable($data = [], $mergeData = []): View|Factory|Response
    {
        if ($this->query()->count() === 0 &&
            ! $this->request()->wantsJson() &&
            $this->request()->input('filter_table_id') !== $this->getOption('id') && ! $this->request()->ajax()
        ) {
            return view('plugins/ecommerce::products.intro');
        }

        return parent::renderTable($data, $mergeData);
    }

    public function getDefaultButtons(): array
    {
        return [
            'reload',
        ];
    }

    public function getCategories(int|string|null $value = null): array
    {
        $categorySelected = [];
        if ($value) {
            $category = app(ProductCategoryInterface::class)->findById($value);
            if ($category) {
                $categorySelected = [$category->id => $category->name];
            }
        }

        return [
            'url' => route('product-categories.search'),
            'selected' => $categorySelected,
            'minimum-input' => 1,
        ];
    }

    public function getFilters(): array
    {
        $data = $this->getBulkChanges();

        $data['category'] = array_merge($data['category'], [
            'type' => 'select-ajax',
            'class' => 'select-search-ajax',
        ]);

        $data['stock_status'] = [
            'title' => trans('plugins/ecommerce::products.form.stock_status'),
            'type' => 'select',
            'choices' => StockStatusEnum::labels(),
            'validate' => 'required|in:' . implode(',', StockStatusEnum::values()),
        ];

        $data['product_type'] = [
            'title' => trans('plugins/ecommerce::products.form.product_type.title'),
            'type' => 'select',
            'choices' => ProductTypeEnum::labels(),
            'validate' => 'required|in:' . implode(',', ProductTypeEnum::values()),
        ];

        return $data;
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'order' => [
                'title' => trans('core/base::tables.order'),
                'type' => 'number',
                'validate' => 'required|min:0',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'select',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'category' => [
                'title' => trans('plugins/ecommerce::products.category'),
                'type' => 'select-ajax',
                'validate' => 'required',
                'callback' => 'getCategories',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }

    public function applyFilterCondition(EloquentBuilder|QueryBuilder|EloquentRelation $query, string $key, string $operator, ?string $value): EloquentRelation|EloquentBuilder|QueryBuilder
    {
        switch ($key) {
            case 'created_at':
                if (! $value) {
                    break;
                }

                $value = Carbon::createFromFormat(config('core.base.general.date_format.date'), $value)->toDateString();

                return $query->whereDate($key, $operator, $value);
            case 'category':
                if (! $value) {
                    break;
                }

                if (! BaseHelper::isJoined($query, 'ec_product_categories')) {
                    $query = $query
                        ->join(
                            'ec_product_category_product',
                            'ec_product_category_product.product_id',
                            '=',
                            'ec_products.id'
                        )
                        ->join(
                            'ec_product_categories',
                            'ec_product_category_product.category_id',
                            '=',
                            'ec_product_categories.id'
                        )
                        ->select($query->getModel()->getTable() . '.*');
                }

                return $query->where('ec_product_category_product.category_id', $value);

            case 'stock_status':
                if (! $value) {
                    break;
                }

                if ($value == StockStatusEnum::ON_BACKORDER) {
                    return parent::applyFilterCondition($query, $key, $operator, $value);
                }

                if ($value == StockStatusEnum::OUT_OF_STOCK) {
                    return $query
                        ->where(function ($query) {
                            $query
                                ->where(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 0)
                                        ->where('stock_status', StockStatusEnum::OUT_OF_STOCK);
                                })
                                ->orWhere(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 1)
                                        ->where('allow_checkout_when_out_of_stock', 0)
                                        ->where('quantity', '<=', 0);
                                });
                        });
                }

                if ($value == StockStatusEnum::IN_STOCK) {
                    return $query
                        ->where(function ($query) {
                            return $query
                                ->where(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 0)
                                        ->where('stock_status', StockStatusEnum::IN_STOCK);
                                })
                                ->orWhere(function ($subQuery) {
                                    $subQuery
                                        ->where('with_storehouse_management', 1)
                                        ->where(function ($sub) {
                                            $sub
                                                ->where('allow_checkout_when_out_of_stock', 1)
                                                ->orWhere('quantity', '>', 0);
                                        });
                                });
                        });
                }
        }

        return parent::applyFilterCondition($query, $key, $operator, $value);
    }

    public function saveBulkChangeItem(Model|Product $item, string $inputKey, ?string $inputValue): Model|bool
    {
        if ($inputKey === 'category') {
            $item->categories()->sync([$inputValue]);

            return $item;
        }

        return parent::saveBulkChangeItem($item, $inputKey, $inputValue);
    }
}
