<?php namespace Modules\Core\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AsgardServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register('Pingpong\Modules\ModulesServiceProvider');
        $this->app->register('Pingpong\Modules\Providers\BootstrapServiceProvider');

        $loader = AliasLoader::getInstance();
        $loader->alias('Module', 'Pingpong\Modules\Facades\Module');
    }
}
