<?php namespace Modules\Core\Composers;

use Maatwebsite\Sidebar\SidebarManager;

class SidebarViewCreator
{
    /**
     * @var SidebarManager
     */
    private $manager;

    /**
     * @param SidebarManager $manager
     */
    public function __construct(SidebarManager $manager)
    {
        $this->manager = $manager;
    }

    public function create($view)
    {
        $view->prefix = config('asgard.core.core.admin-prefix');
        $view->sidebar = $this->manager->build();
    }
}
