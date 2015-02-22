<?php namespace Modules\Core\Tests\Asset;

use Modules\Core\Foundation\Asset\Manager\AsgardAssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AsgardAssetPipeline;
use Modules\Core\Tests\BaseTestCase;

class AsgardAssetPipelineTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Asset\Pipeline\AsgardAssetPipeline
     */
    private $assetPipeline;
    /**
     * @var \Modules\Core\Foundation\Asset\Manager\AsgardAssetManager
     */
    private $assetManager;

    /**
     *
     */
    public function setUp()
    {
        parent::__construct();
        $this->refreshApplication();
        $this->assetPipeline = new AsgardAssetPipeline($this->app['Modules\Core\Foundation\Asset\Manager\AssetManager']);
        $this->assetManager = new AsgardAssetManager();
    }

    /** @test */
    public function it_should_require_add_js_asset()
    {
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');

        $this->assetPipeline->requireJs('jquery');

        $jsAssets = $this->assetPipeline->allJs();

        $this->assertEquals('/path/to/jquery.js', $jsAssets->first());
    }
}
