<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PushService;

class PushServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     * @author LaravelAcademy.org
     */
    public function register()
    {
        $this->app->singleton('push',function(){
            return new PushService();
        });
    }
}