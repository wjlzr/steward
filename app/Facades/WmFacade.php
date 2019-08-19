<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WmFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wm';
    }
}