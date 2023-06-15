<?php

namespace Botble\Ecommerce\Repositories\Caches;

use Botble\Ecommerce\Repositories\Interfaces\CurrencyInterface;
use Botble\Support\Repositories\Caches\CacheAbstractDecorator;

class CurrencyCacheDecorator extends CacheAbstractDecorator implements CurrencyInterface
{
    public function getAllCurrencies()
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }
}
