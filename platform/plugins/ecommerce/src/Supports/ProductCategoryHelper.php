<?php

namespace Botble\Ecommerce\Supports;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Supports\SortItemsWithChildrenHelper;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Language;

class ProductCategoryHelper
{
    protected Collection|array $allCategories = [];

    protected Collection|array $treeCategories = [];

    public function getAllProductCategories(array $params = [], bool $onlyParent = false): Collection
    {
        if (! $this->allCategories instanceof Collection) {
            $this->allCategories = collect();
        }

        if ($this->allCategories->count() == 0) {
            $with = array_merge(Arr::get($params, 'with', []), ['slugable', 'metadata']);
            if (is_plugin_active('language-advanced') && Language::getCurrentLocaleCode() != Language::getDefaultLocaleCode()) {
                $with[] = 'translations';
            }

            $this->allCategories = app(ProductCategoryInterface::class)->getProductCategories(
                Arr::get($params, 'condition', []),
                $with,
                Arr::get($params, 'withCount', []),
                $onlyParent
            );
        }

        return $this->allCategories;
    }

    public function getAllProductCategoriesSortByChildren(): Collection
    {
        $categories = $this->getAllProductCategories();

        return $this->sortChildren($categories);
    }

    public function getAllProductCategoriesWithChildren(): array
    {
        $categories = $this->getAllProductCategories();

        return app(SortItemsWithChildrenHelper::class)
            ->setChildrenProperty('child_cats')
            ->setItems($categories)
            ->sort();
    }

    protected function sortChildren(Collection $categories, $parent = null, int $depth = 0): Collection
    {
        foreach ($categories as &$object) {
            if ($object->parent_id == $object->id) {
                continue;
            }

            if ((int)$object->parent_id == (int)$parent) {
                $object->depth = $depth;
                $this->sortChildren($categories, $object->id, $depth + 1);
            }
        }

        return $categories;
    }

    public function getProductCategoriesWithIndent(string $indent = '&nbsp;&nbsp;', bool $sortChildren = true): Collection
    {
        $categories = $this->getAllProductCategoriesSortByChildren();

        foreach ($categories as $category) {
            $depth = (int)$category->depth;

            $indentText = str_repeat($indent, $depth);

            $category->indent_text = $indentText;
        }

        if (! $sortChildren) {
            return $categories;
        }

        return collect(sort_item_with_children($categories));
    }

    public function getProductCategoriesWithIndentName(Collection|array $categories = [], string $indent = '&nbsp;&nbsp;'): array
    {
        if (! $categories instanceof Collection) {
            $categories = $this->getAllProductCategories([], true);
        }

        $results = [];
        $this->appendIndentTextToProductCategoryName($categories, 0, $results, $indent);

        return $results;
    }

    public function appendIndentTextToProductCategoryName(
        Collection $categories,
        int $depth = 0,
        array &$results = [],
        string $indent = '&nbsp;&nbsp;'
    ): bool {
        foreach ($categories as $category) {
            $results[$category->id] = str_repeat($indent, $depth) . $category->name;

            if ($category->children->count()) {
                $this->appendIndentTextToProductCategoryName($category->children, $depth + 1, $results, $indent);
            }
        }

        return true;
    }

    public function getActiveTreeCategories(): Collection
    {
        if (! $this->treeCategories instanceof Collection) {
            $this->treeCategories = collect();
        }

        if ($this->treeCategories->count() == 0) {
            $this->treeCategories = $this->getAllProductCategories(
                [
                    'condition' => ['status' => BaseStatusEnum::PUBLISHED],
                    'with' => ['activeChildren'],
                ],
                true
            );
        }

        return $this->treeCategories;
    }
}
