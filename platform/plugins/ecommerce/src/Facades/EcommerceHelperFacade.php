<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\EcommerceHelper;
use Illuminate\Support\Facades\Facade;

class EcommerceHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return EcommerceHelper::class;
    }
}
