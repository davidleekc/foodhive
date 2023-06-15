<?php

namespace Botble\Payment\Facades;

use Botble\Payment\Supports\PaymentMethods;
use Illuminate\Support\Facades\Facade;

class PaymentMethodsFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return PaymentMethods::class;
    }
}
