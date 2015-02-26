<?php namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;

class AdminBaseController extends Controller
{
    /**
     * @var AssetManager
     */
    private $assetManager;
    /**
     * @var AssetPipeline
     */
    private $assetPipeline;
    /**
     * @var \FloatingPoint\Stylist\Html\ThemeHtmlBuilder
     */
    private $theme;

    public function __construct()
    {
        $this->assetManager = app('Modules\Core\Foundation\Asset\Manager\AssetManager');
        $this->assetPipeline = app('Modules\Core\Foundation\Asset\Pipeline\AssetPipeline');
        $this->theme = app('FloatingPoint\Stylist\Html\ThemeHtmlBuilder');

        $this->addAssets();
    }

    /**
     * Add the assets from the config file on the asset manager
     */
    private function addAssets()
    {
        $this->assetManager->addAssets(config('asgard.core.core.admin-assets'));
    }
}
