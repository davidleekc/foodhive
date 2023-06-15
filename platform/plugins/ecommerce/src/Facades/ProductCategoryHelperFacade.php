<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\ProductCategoryHelper;
use Illuminate\Support\Facades\Facade;

class ProductCategoryHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ProductCategoryHelper::class;
    }
}
