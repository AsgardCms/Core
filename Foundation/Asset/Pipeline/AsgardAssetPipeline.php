<?php namespace Modules\Core\Foundation\Asset\Pipeline;

class AsgardAssetPipeline implements AssetPipeline
{
    /**
     * Add a javascript dependency on the view
     * @param string $dependency
     * @return $this
     */
    public function requireJs($dependency)
    {
    }

    /**
     * Add a CSS dependency on the view
     * @param string $dependency
     * @return $this
     */
    public function requireCss($dependency)
    {
    }

    /**
     * Add the dependency after another one
     * @param string $dependency
     * @return void
     */
    public function after($dependency)
    {
    }
}
