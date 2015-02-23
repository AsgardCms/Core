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
        $this->assetManager = $this->app['Modules\Core\Foundation\Asset\Manager\AssetManager'];
    }

    /** @test */
    public function it_should_return_empty_collection_if_no_assets()
    {
        $cssResult = $this->assetPipeline->allCss();
        $jsResult = $this->assetPipeline->allJs();

        $this->assertInstanceOf('Illuminate\Support\Collection', $cssResult);
        $this->assertEquals(0, $cssResult->count());
        $this->assertInstanceOf('Illuminate\Support\Collection', $jsResult);
        $this->assertEquals(0, $jsResult->count());
    }

    /** @test */
    public function it_should_require_add_js_asset()
    {
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');

        $this->assetPipeline->requireJs('jquery');

        $jsAssets = $this->assetPipeline->allJs();

        $this->assertEquals('/path/to/jquery.js', $jsAssets->first());
    }

    /** @test */
    public function it_should_require_a_css_asset()
    {
        $this->assetManager->addAsset('main', '/path/to/main.css');

        $this->assetPipeline->requireCss('main');

        $cssAssets = $this->assetPipeline->allCss();

        $this->assertEquals('/path/to/main.css', $cssAssets->first());
    }
}
