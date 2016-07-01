<?php

namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;
use Modules\Core\Contracts\Setting;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;

class SiteNameViewComposer
{
    /**
     * @var Setting
     */
    private $setting;
    /**
     * @var AssetPipeline
     */
    private $assetPipeline;

    public function __construct(Setting $setting, AssetPipeline $assetPipeline)
    {
        $this->setting = $setting;
        $this->assetPipeline = $assetPipeline;
    }

    public function compose(View $view)
    {
        $view->with('sitename', $this->setting->get('core::site-name', App::getLocale()));
    }
}
