<?php

namespace Pyrocmsapi\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouterServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Pyrocmsapi\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->aliasMiddleware('api.response', \Pyrocmsapi\Http\Middleware\BuildHttpResponse::class);
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        //
    }


    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group(
            [
                'middleware' => ['api','api.response'],
                'namespace'  => $this->namespace,
                'prefix'     => 'api',
            ],
            function ($router) {
                require realpath(__DIR__ . '/../Routes/api.php');
            }
        );
    }
}
