<?php namespace Modules\Core\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Modules\Translation\Providers\TranslationServiceProvider;

class AsgardServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (class_exists(TranslationServiceProvider::class)) {
            $this->app->register(TranslationServiceProvider::class);
        }
        $this->app->register('Pingpong\Modules\ModulesServiceProvider');

        $loader = AliasLoader::getInstance();
        $loader->alias('Module', 'Pingpong\Modules\Facades\Module');
    }
}
