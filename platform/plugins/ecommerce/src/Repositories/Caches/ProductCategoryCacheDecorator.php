<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductCategoryCacheDecorator extends CacheAbstractDecorator implements ProductCategoryInterface
{
    public function getCategories(array $param)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getDataSiteMap()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getFeaturedCategories($limit)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllCategories($active = true)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getProductCategories(
        array $conditions = [],
        array $with = [],
        array $withCount = [],
        bool $parentOnly = false
    ) {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
