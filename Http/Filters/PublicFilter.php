<?php namespace Modules\Core\Http\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Modules\Menu\Repositories\MenuItemRepository;

class PublicFilter
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var MenuItemRepository
     */
    private $menuItem;

    public function __construct(Request $request, MenuItemRepository $menuItem)
    {
        $this->request = $request;
        $this->menuItem = $menuItem;
    }

    public function filter()
    {
        $locale = $this->request->segment(1) ?: App::getLocale();
        $item = $this->menuItem->findByUriInLanguage($this->request->segment(2), $locale);

        if ($this->isOffline($item)) {
            App::abort(404);
        }
    }

    /**
     * Checks if the given menu item is offline
     * @param object $item
     * @return bool
     */
    private function isOffline($item)
    {
        return is_null($item);
    }
}