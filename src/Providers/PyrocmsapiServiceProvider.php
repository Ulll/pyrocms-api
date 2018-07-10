<?php

namespace Pyrocmsapi\Providers;

use Illuminate\Support\ServiceProvider;

class PyrocmsapiServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

    }


    public function register()
    {
        $this->app->register(\Pyrocmsapi\Providers\RouterServiceProvider::class);
    }
}
