<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\AllowedIPMiddleware;
use App\Http\Middleware\ValidateRequestMethodMiddleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Router $router)
    {
        // Register route middleware aliases
        $router->aliasMiddleware('allowed.ip', AllowedIPMiddleware::class);
        $router->aliasMiddleware('check.request.method', ValidateRequestMethodMiddleware::class);
    }
}
