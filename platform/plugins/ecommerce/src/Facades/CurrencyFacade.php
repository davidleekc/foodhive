<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\CurrencySupport;
use Illuminate\Support\Facades\Facade;

class CurrencyFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return CurrencySupport::class;
    }
}
