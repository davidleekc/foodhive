<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\OrderReturnHelper;
use Illuminate\Support\Facades\Facade;

class OrderReturnHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OrderReturnHelper::class;
    }
}
