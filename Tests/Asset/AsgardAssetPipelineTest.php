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

    /** @test */
    public function it_should_return_js_assets_in_right_order()
    {
        $this->assetManager->addAsset('mega_slider', '/path/to/mega_slider.js');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('iCheck', '/path/to/iCheck.css');
        $this->assetManager->addAsset('bootstrap', '/path/to/bootstrap.css');

        $this->assetPipeline->requireJs('jquery');
        $this->assetPipeline->requireJs('mega_slider');
        $this->assetPipeline->requireJs('jquery_plugin')->after('jquery');

        $jsAssets = $this->assetPipeline->allJs();

        $jquery = $jsAssets->pull('jquery');
        $jqueryPlugin = $jsAssets->first();

        $this->assertEquals($jquery, '/path/to/jquery.js');
        $this->assertEquals($jqueryPlugin, '/path/to/jquery_plugin.js');
    }

    /** @test */
    public function it_should_return_css_assets_in_right_order()
    {
        $this->assetManager->addAsset('mega_slider', '/path/to/mega_slider.js');
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');
        $this->assetManager->addAsset('jquery_plugin', '/path/to/jquery_plugin.js');
        $this->assetManager->addAsset('main', '/path/to/main.css');
        $this->assetManager->addAsset('iCheck', '/path/to/iCheck.css');
        $this->assetManager->addAsset('bootstrap', '/path/to/bootstrap.css');

        $this->assetPipeline->requireCss('bootstrap');
        $this->assetPipeline->requireCss('iCheck');
        $this->assetPipeline->requireCss('main')->after('bootstrap');

        $cssAssets = $this->assetPipeline->allCss();

        $bootstrap = $cssAssets->pull('bootstrap');
        $main = $cssAssets->first();

        $this->assertEquals($bootstrap, '/path/to/bootstrap.css');
        $this->assertEquals($main, '/path/to/main.css');
    }
}
