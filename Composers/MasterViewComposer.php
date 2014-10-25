<?php namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Modules\Setting\Support\Settings;

class MasterViewComposer
{
    /**
     * @var Settings
     */
    private $settingReader;

    public function __construct(Settings $settingReader)
    {
        $this->settingReader = $settingReader;
    }

    public function compose(View $view)
    {
        $view->with('sitename', $this->settingReader->get('core::site-name', App::getLocale()));
    }
}
