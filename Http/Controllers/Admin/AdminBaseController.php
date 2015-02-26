<?php namespace Modules\Core\Http\Controllers\Admin;

use Illuminate\Routing\Controller;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;

class AdminBaseController extends Controller
{
    /**
     * @var AssetManager
     */
    protected $assetManager;
    /**
     * @var AssetPipeline
     */
    protected $assetPipeline;

    public function __construct()
    {
        $this->assetManager = app('Modules\Core\Foundation\Asset\Manager\AssetManager');
        $this->assetPipeline = app('Modules\Core\Foundation\Asset\Pipeline\AssetPipeline');

        $this->addAssets();
        $this->requireDefaultAssets();
    }

    /**
     * Add the assets from the config file on the asset manager
     */
    private function addAssets()
    {
        $this->assetManager->addAssets(config('asgard.core.core.admin-assets'));
    }

    /**
     * Require the default assets from config file on the asset pipeline
     */
    private function requireDefaultAssets()
    {
        $this->assetPipeline->requireCss(config('asgard.core.core.admin-required-assets.css'));
        $this->assetPipeline->requireJs(config('asgard.core.core.admin-required-assets.js'));
    }
}
