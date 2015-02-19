<?php namespace Modules\Core\Foundation\Asset\Manager;

interface AssetManager
{
    /**
     * Add a possible asset
     * @param string $dependency
     * @param string $path
     * @return void
     */
    public function addAsset($dependency, $path);

    /**
     * Return all css files to include
     * @return \Illuminate\Support\Collection
     */
    public function allCss();

    /**
     * Return all js files to include
     * @return \Illuminate\Support\Collection
     */
    public function allJs();
}
