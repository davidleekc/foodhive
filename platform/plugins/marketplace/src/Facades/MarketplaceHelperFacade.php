<?php

namespace Botble\Marketplace\Facades;

use Botble\Marketplace\Supports\MarketplaceHelper;
use Illuminate\Support\Facades\Facade;

class MarketplaceHelperFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MarketplaceHelper::class;
    }
}
