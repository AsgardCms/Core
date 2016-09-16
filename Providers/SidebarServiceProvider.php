<?php namespace Modules\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\SidebarManager;
use Modules\Core\Sidebar\AdminSidebar;
use Illuminate\Http\Request;

class SidebarServiceProvider extends ServiceProvider
{
    protected $defer = true;
    private $request;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
    }

    public function boot(SidebarManager $manager, Request $request)
    {
        $this->request = $request;
        if ($this->onBackend() === true ) {
            $manager->register(AdminSidebar::class);
        }
    }

    private function onBackend()
    {
        $segment = $this->request->segment(1);
        if (str_contains($segment, config('asgard.core.core.admin-prefix'))) {
            return true;
        }
        return false;
    }
}
