<?php namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Foundation\Asset\Manager\AsgardAssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AsgardAssetPipeline;

class AssetServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        $this->bindAssetClasses();
    }

    /**
     * Bind classes related to assets
     */
    private function bindAssetClasses()
    {
        $this->app->singleton('Modules\Core\Foundation\Asset\Manager\AssetManager', function () {
            return new AsgardAssetManager();
        });

        $this->app->singleton('Modules\Core\Foundation\Asset\Pipeline\AssetPipeline', function ($app) {
            return new AsgardAssetPipeline($app['Modules\Core\Foundation\Asset\Manager\AssetManager']);
        });
    }
}
