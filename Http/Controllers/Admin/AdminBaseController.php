<?php namespace Modules\Core\Http\Controllers\Admin;

use FloatingPoint\Stylist\Facades\ThemeFacade as Theme;
use Illuminate\Routing\Controller;
use Modules\Core\Foundation\Asset\Manager\AssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AssetPipeline;
use Modules\Core\Foundation\Asset\Types\AssetTypeFactory;
use Pingpong\Modules\Facades\Module;

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
    /**
     * @var AssetTypeFactory
     */
    protected $assetFactory;

    public function __construct()
    {
        $this->assetManager = app(AssetManager::class);
        $this->assetPipeline = app(AssetPipeline::class);
        $this->assetFactory = app(AssetTypeFactory::class);

        $this->addAssets();
    }

    /**
     * Add the assets from the config file on the asset manager
     */
    private function addAssets()
    {
        foreach (config('asgard.core.core.admin-assets') as $assetName => $path) {
            $path = $this->assetFactory->make($path)->url();
            $this->assetManager->addAsset($assetName, $path);
        }
    }
}
