<?php

namespace Botble\SimpleSlider\Tables;

use BaseHelper;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\SimpleSlider\Repositories\Interfaces\SimpleSliderInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class SimpleSliderTable extends TableAbstract
{
    protected $hasActions = true;

    protected $hasFilter = true;

    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        SimpleSliderInterface $simpleSliderRepository
    ) {
        parent::__construct($table, $urlGenerator);

        $this->repository = $simpleSliderRepository;

        if (! Auth::user()->hasAnyPermission(['simple-slider.edit', 'simple-slider.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    public function ajax(): \Illuminate\Http\JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function ($item) {
                if (! Auth::user()->hasPermission('simple-slider.edit')) {
                    return BaseHelper::clean($item->name);
                }

                return Html::link(route('simple-slider.edit', $item->id), BaseHelper::clean($item->name));
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('simple-slider.edit', 'simple-slider.destroy', $item);
            });

        if (function_exists('shortcode')) {
            $data = $data->editColumn('key', function ($item) {
                return shortcode()->generateShortcode('simple-slider', ['key' => $item->key]);
            });
        }

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this->repository->getModel()->select([
            'id',
            'name',
            'key',
            'status',
            'created_at',
        ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            'id' => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'name' => [
                'title' => trans('core/base::tables.name'),
                'class' => 'text-start',
            ],
            'key' => [
                'title' => trans('plugins/simple-slider::simple-slider.key'),
                'class' => 'text-start',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('simple-slider.create'), 'simple-slider.create');
    }

    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('simple-slider.deletes'), 'simple-slider.destroy', parent::bulkActions());
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.name'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'key' => [
                'title' => trans('plugins/simple-slider::simple-slider.key'),
                'type' => 'text',
                'validate' => 'required|max:120',
            ],
            'status' => [
                'title' => trans('core/base::tables.status'),
                'type' => 'customSelect',
                'choices' => BaseStatusEnum::labels(),
                'validate' => 'required|' . Rule::in(BaseStatusEnum::values()),
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type' => 'datePicker',
            ],
        ];
    }
}
