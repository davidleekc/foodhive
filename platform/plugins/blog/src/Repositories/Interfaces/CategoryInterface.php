<?php

namespace Botble\Blog\Repositories\Interfaces;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Blog\Models\Category;
use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Eloquent;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryInterface extends RepositoryInterface
{
    public function getDataSiteMap(): Collection;

    public function getFeaturedCategories(?int $limit, array $with = []): Collection;

    public function getAllCategories(array $condition = [], array $with = []): Collection;

    public function getCategoryById(int|string|null $id): ?Category;

    public function getCategories(array $select, array $orderBy, array $conditions = ['status' => BaseStatusEnum::PUBLISHED]): Collection;

    public function getAllRelatedChildrenIds(int|string|null|Eloquent $id): array;

    public function getAllCategoriesWithChildren(array $condition = [], array $with = [], array $select = ['*']): Collection;

    public function getFilters(array $filters): LengthAwarePaginator;

    public function getPopularCategories(int $limit, array $with = ['slugable'], array $withCount = ['posts']): Collection;
}
