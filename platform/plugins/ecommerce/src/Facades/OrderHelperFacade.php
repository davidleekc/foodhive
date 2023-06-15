<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\OrderHelper;
use Illuminate\Support\Facades\Facade;

class OrderHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OrderHelper::class;
    }
}
