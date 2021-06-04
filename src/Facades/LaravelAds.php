<?php

namespace LaravelAds\Facades;

use LaravelAds\LaravelAds as AliasLaravelAds;
use Illuminate\Support\Facades\Facade;

class LaravelAds extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AliasLaravelAds::class;
    }
}
