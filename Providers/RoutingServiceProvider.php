<?php namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;

class RoutingServiceProvider extends ServiceProvider
{
    public function before(Router $router)
    {
        // Intercept the router and check if public routes are available
    }

    public function map(Router $router)
    {
    }
}
