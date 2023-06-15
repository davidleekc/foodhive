<?php

namespace Botble\SocialLogin\Facades;

use Botble\SocialLogin\Supports\SocialService;
use Illuminate\Support\Facades\Facade;

/**
 * @see \Botble\SocialLogin\Supports\SocialService
 */
class SocialServiceFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SocialService::class;
    }
}
