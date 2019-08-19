<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Wm\WmService;

class WmServiceProvider extends ServiceProvider
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
        $this->app->singleton('wm',function(){
            return new WmService();
        });
    }
}