<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PushFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'push';
    }
}