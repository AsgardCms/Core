<?php namespace Modules\Core\Tests\Asset;

use Modules\Core\Foundation\Asset\Manager\AsgardAssetManager;
use Modules\Core\Foundation\Asset\Pipeline\AsgardAssetPipeline;
use Modules\Core\Tests\BaseTestCase;

abstract class AsgardAssetPipelineTest extends BaseTestCase
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
        $this->assetPipeline = new AsgardAssetPipeline();
        $this->assetManager = new AsgardAssetManager();
    }
}
