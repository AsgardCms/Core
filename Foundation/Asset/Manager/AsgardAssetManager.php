<?php namespace Modules\Core\Foundation\Asset\Manager;

use Illuminate\Support\Collection;

final class AsgardAssetManager implements AssetManager
{
    /**
     * @var array
     */
    protected $css = [];
    /**
     * @var array
     */
    protected $js = [];

    public function __construct()
    {
        $this->css = new Collection();
        $this->js = new Collection();
    }

    /**
     * Add a possible asset
     * @param string $dependency
     * @param string $path
     * @return void
     */
    public function addAsset($dependency, $path)
    {
        if ($this->isJs($path)) {
            return $this->js->put($dependency, $path);
        }
        if ($this->isCss($path)) {
            return $this->css->put($dependency, $path);
        }
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

    /**
     * Check if the given path is a javascript file
     * @param string $path
     * @return bool
     */
    private function isJs($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION) == 'js';
    }

    /**
     * Check if the given path is a css file
     * @param string $path
     * @return bool
     */
    private function isCss($path)
    {
        return pathinfo($path, PATHINFO_EXTENSION) == 'css';
    }

    /**
     * @param string $dependency
     * @return string
     */
    public function getJs($dependency)
    {
        return $this->js->get($dependency);
    }

    /**
     * @param string $dependency
     * @return string
     */
    public function getCss($dependency)
    {
        return $this->css->get($dependency);
    }
}
