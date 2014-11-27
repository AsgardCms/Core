<?php namespace Modules\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class RoutingServiceProvider extends ServiceProvider
{
    public function before(Router $router)
    {
    }

    public function map(Router $router)
    {
    }
}
