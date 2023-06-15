<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\ProductAttributeSetInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class ProductAttributeSetCacheDecorator extends CacheAbstractDecorator implements ProductAttributeSetInterface
{
    public function getByProductId($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

    public function getAllWithSelected($productId)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
