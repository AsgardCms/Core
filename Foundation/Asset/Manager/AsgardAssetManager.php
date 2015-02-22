<?php namespace Modules\Core\Foundation\Asset\Manager;

use Illuminate\Support\Collection;

class AsgardAssetManager implements AssetManager
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
//        $extension = pathinfo($path, PATHINFO_EXTENSION);
//        if ($extension == 'js') {
//            //$this->js =
//        }
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
