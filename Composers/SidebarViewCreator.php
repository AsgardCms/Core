<?php namespace Modules\Core\Composers;

use Illuminate\Support\Collection;

class SidebarViewCreator
{
    public function create($view)
    {
        $view->prefix = config('asgard.core.core.admin-prefix');
        $view->items = new Collection();
    }
}
