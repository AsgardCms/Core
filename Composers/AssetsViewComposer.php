<?php namespace Modules\Core\Composers;

use Illuminate\Contracts\View\View;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;
use Modules\Core\Foundation\Asset\Types\AssetTypeFactory;

class AssetsViewComposer
{
    /**
     * @var AssetManager
     */
    protected $assetManager;
    /**
     * @var AssetPipeline
     */
    protected $assetPipeline;
    /**
     * @var AssetTypeFactory
     */
    protected $assetFactory;

    public function __construct(AssetManager $assetManager, AssetPipeline $assetPipeline, AssetTypeFactory $assetTypeFactory)
    {
        $this->assetManager = $assetManager;
        $this->assetPipeline = $assetPipeline;
        $this->assetFactory = $assetTypeFactory;
    }

    public function compose(View $view)
    {
        $this->requireDefaultAssets();

        $view->with('cssFiles', $this->assetPipeline->allCss());
        $view->with('jsFiles', $this->assetPipeline->allJs());
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
