<?php

namespace Botble\Ecommerce\Facades;

use Botble\Ecommerce\Supports\InvoiceHelper;
use Illuminate\Support\Facades\Facade;

class InvoiceHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return InvoiceHelper::class;
    }
}
