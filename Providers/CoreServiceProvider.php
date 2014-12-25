<?php namespace Modules\Core\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Modules\Core\Console\InstallCommand;
use Modules\Core\Console\PublishModuleAssetsCommand;
use Modules\Core\Console\PublishThemeAssetsCommand;
use Modules\Core\Foundation\Theme\ThemeManager;
use Modules\Core\Services\Composer;
use Modules\Menu\Entities\Menuitem;
use Modules\Menu\Repositories\Cache\CacheMenuItemDecorator;
use Modules\Menu\Repositories\Eloquent\EloquentMenuItemRepository;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * The filters base class name.
     *
     * @var array
     */
    protected $filters = [
        'Core' => [
            'permissions' => 'PermissionFilter',
            'auth.admin' => 'AdminFilter',
        ]
    ];

    public function boot()
    {
        include __DIR__ . '/../start.php';
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAliases();
        $this->registerMenuRoutes();
        $this->registerFilters($this->app['router']);
        $this->registerCommands();
        $this->registerServices();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerFilters(Router $router)
    {
        foreach ($this->filters as $module => $filters) {
            foreach ($filters as $name => $filter) {
                $class = "Modules\\{$module}\\Http\\Filters\\{$filter}";

                $router->filter($name, $class);
            }
        }
    }

    /**
     * Register the console commands
     */
    private function registerCommands()
    {
        $this->registerInstallCommand();
        $this->registerThemeCommand();
        $this->registerPublishModuleAssetsCommand();

        $this->commands([
            'command.asgard.install',
            'command.asgard.publish.theme',
            'command.asgard.publish.module.assets',
        ]);
    }

    /**
     * Register the installation command
     */
    private function registerInstallCommand()
    {
        $this->app->bindShared('command.asgard.install', function($app) {
            return new InstallCommand(
                $app['files'],
                $app,
                new Composer($app['files'])
            );
        });
    }

    private function registerThemeCommand()
    {
        $this->app->bindShared('command.asgard.publish.theme', function($app) {
            return new PublishThemeAssetsCommand(new ThemeManager($app, $app['config']->get('themify::themes_path')));
        });
    }

    private function registerPublishModuleAssetsCommand()
    {
        $this->app->bindShared('command.asgard.publish.module.assets', function() {
            return new PublishModuleAssetsCommand;
        });
    }

    private function registerMenuRoutes()
    {
        $this->app->bind(
            'Modules\Menu\Repositories\MenuItemRepository',
            function() {
                $repository = new EloquentMenuItemRepository(new Menuitem);

                if (! Config::get('app.cache')) {
                    return $repository;
                }

                return new CacheMenuItemDecorator($repository);
            }
        );
        $this->app->singleton('Asgard.routes', function (Application $app) {
            return $app->make('Modules\Menu\Repositories\MenuItemRepository')->getForRoutes();
        });
    }

    private function registerServices()
    {
        $this->app->bindShared('asgard.themes', function ($app) {
            $path = $app['config']->get('themify::themes_path');
            return new ThemeManager($app, $path);
        });
    }

    /**
     * Register the modules aliases
     */
    private function registerAliases()
    {
        $this->app->booted(function($app) {
            $modules = $app['modules']->enabled();
            $loader = AliasLoader::getInstance();
            foreach ($modules as $moduleName => $module) {
                if ($aliases = $module->get('aliases')) {
                    foreach ($aliases as $aliasName => $aliasClass) {
                        $loader->alias($aliasName, $aliasClass);
                    }
                }
            }
        });
    }
}
