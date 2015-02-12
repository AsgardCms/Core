<?php namespace Modules\Core\Http\Controllers;

use FloatingPoint\Stylist\Facades\StylistFacade as Stylist;
use Illuminate\Routing\Controller;
use Modules\Core\Contracts\Setting;

abstract class BasePublicController extends Controller
{
    /**
     * @var string The active theme name
     */
    public $theme;
    /**
     * @var Setting
     */
    private $setting;

    public function __construct()
    {
        $this->setting = app('setting.settings');
        Stylist::registerPath(base_path('Themes/' . $this->setting->get('core::template')), true);
    }
}
