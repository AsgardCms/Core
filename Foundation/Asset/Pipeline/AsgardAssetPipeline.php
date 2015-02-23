<?php namespace Modules\Core\Foundation\Asset\Pipeline;

use Illuminate\Support\Collection;
use Modules\Core\Foundation\Asset\Manager\AssetManager;

class AsgardAssetPipeline implements AssetPipeline
{
    /**
     * @var array
     */
    protected $css;
    /**
     * @var array
     */
    protected $js;

    public function __construct(AssetManager $assetManager)
    {
        $this->css = new Collection();
        $this->js = new Collection();
        $this->assetManager = $assetManager;
    }

    /**
     * Add a javascript dependency on the view
     * @param string $dependency
     * @return $this
     */
    public function requireJs($dependency)
    {
        $this->js->put($dependency, $this->assetManager->getJs($dependency));
    }

    /**
     * Add a CSS dependency on the view
     * @param string $dependency
     * @return $this
     */
    public function requireCss($dependency)
    {
        $this->css->put($dependency, $this->assetManager->getCss($dependency));
    }

    /**
     * Add the dependency after another one
     * @param string $dependency
     * @return void
     */
    public function after($dependency)
    {
    }

    /**
     * Return all css files to include
     * @return \Illuminate\Support\Collection
     */
    public function allCss()
    {
        return $this->css;
    }

    /**
     * Return all js files to include
     * @return \Illuminate\Support\Collection
     */
    public function allJs()
    {
        return $this->js;
    }
}
