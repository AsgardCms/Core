<?php namespace Modules\Core\Tests;

use Orchestra\Testbench\TestCase;

abstract class BaseTestCase extends TestCase
{
    protected $app;

    public function setUp()
    {
        parent::setUp();
        $this->refreshApplication();
    }

    protected function getPackageProviders($app)
    {
        return [
            'Pingpong\Modules\ModulesServiceProvider',
            'Modules\Core\Providers\CoreServiceProvider',
            'Modules\Core\Providers\AssetServiceProvider',
            'Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider',
            'Maatwebsite\Sidebar\SidebarServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = __DIR__ . '/..';
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', array(
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ));
        $app['config']->set('translatable.locales', ['en', 'fr']);
    }

    protected function getPackageAliases($app)
    {
        return [
            'Eloquent' => 'Illuminate\Database\Eloquent\Model',
            'LaravelLocalization' => 'Mcamara\LaravelLocalization\Facades\LaravelLocalization'
        ];
    }
}
