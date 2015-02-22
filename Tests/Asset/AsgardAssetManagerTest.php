<?php namespace Modules\Core\Tests\Asset;

use Modules\Core\Foundation\Asset\Manager\AsgardAssetManager;
use Modules\Core\Tests\BaseTestCase;

class AsgardAssetManagerTest extends BaseTestCase
{
    /**
     * @var \Modules\Core\Foundation\Asset\Manager\AsgardAssetManager
     */
    private $assetManager;

    public function setUp()
    {
        parent::__construct();
        $this->refreshApplication();
        $this->assetManager = new AsgardAssetManager();
    }

    /** @test */
    public function it_should_return_empty_collection_if_no_assets()
    {
        $cssResult = $this->assetManager->allCss();
        $jsResult = $this->assetManager->allJs();

        $this->assertInstanceOf('Illuminate\Support\Collection', $cssResult);
        $this->assertEquals(0, $cssResult->count());
        $this->assertInstanceOf('Illuminate\Support\Collection', $jsResult);
        $this->assertEquals(0, $jsResult->count());
    }

    /** @test */
    public function it_should_add_one_javascript_asset()
    {
        $this->assetManager->addAsset('jquery', '/path/to/jquery.js');

        $jsResult = $this->assetManager->allJs();

        $this->assertEquals(1, $jsResult->count());
    }

    /** @test */
    public function it_should_add_one_css_asset()
    {
        $this->assetManager->addAsset('main', '/path/to/main.css');

        $cssResult = $this->assetManager->allCss();

        $this->assertEquals(1, $cssResult->count());
    }
}
