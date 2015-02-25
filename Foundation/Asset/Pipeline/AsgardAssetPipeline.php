<?php namespace Modules\Core\Foundation\Asset\Pipeline;

use Illuminate\Support\Collection;
use Modules\Core\Foundation\Asset\Manager\AssetManager;

class AsgardAssetPipeline implements AssetPipeline
{
    /**
     * @var Collection
     */
    protected $css;
    /**
     * @var Collection
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

        return $this;
    }

    /**
     * Add a CSS dependency on the view
     * @param string $dependency
     * @return $this
     */
    public function requireCss($dependency)
    {
        $this->css->put($dependency, $this->assetManager->getCss($dependency));

        return $this;
    }

    /**
     * Add the dependency after another one
     * @param string $dependency
     * @return void
     */
    public function after($dependency)
    {
        list($dependencyArray, $collectionName) = $this->findDependenciesForKey($dependency);
        list($key, $value) = $this->getLastKeyAndValueOf($dependencyArray);

        $pos = $this->getPositionInArray($dependency, $dependencyArray);

        $dependencyArray = array_merge(
            array_slice($dependencyArray, 0, $pos + 1, true),
            [$key => $value],
            array_slice($dependencyArray, $pos, count($dependencyArray) - 1, true)
        );

        $this->$collectionName = new Collection($dependencyArray);
    }

    /**
     * Add the dependency before another one
     * @param string $dependency
     * @return void
     */
    public function before($dependency)
    {
        list($dependencyArray, $collectionName) = $this->findDependenciesForKey($dependency);
        list($key, $value) = $this->getLastKeyAndValueOf($dependencyArray);

        $pos = $this->getPositionInArray($dependency, $dependencyArray);

        $dependencyArray = array_merge(
            array_slice($dependencyArray, 0, $pos, true),
            [$key => $value],
            array_slice($dependencyArray, $pos, count($dependencyArray) - 1, true)
        );

        $this->$collectionName = new Collection($dependencyArray);
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
     * Find in which collection the given dependency exists
     * @param string $dependency
     * @return array
     */
    private function findDependenciesForKey($dependency)
    {
        if ($this->css->get($dependency)) {
            return [$this->css->toArray(), 'css'];
        }

        return [$this->js->toArray(), 'js'];
    }

    /**
     * Get the last key and value the given array
     * @param array $dependencyArray
     * @return array
     */
    private function getLastKeyAndValueOf(array $dependencyArray)
    {
        $value = end($dependencyArray);
        $key = key($dependencyArray);
        reset($dependencyArray);

        return [$key, $value];
    }

    /**
     * Return the position in the array of the given key
     *
     * @param $dependency
     * @param array $dependencyArray
     * @return int
     */
    private function getPositionInArray($dependency, array $dependencyArray)
    {
        $pos = array_search($dependency, array_keys($dependencyArray));

        return $pos;
    }
}
