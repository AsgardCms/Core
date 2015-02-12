<?php namespace Modules\Core\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Core\Contracts\Setting;
use Nwidart\Themify\Facades\Themify;

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
        Themify::set($this->setting->get('core::template'));
    }
}
