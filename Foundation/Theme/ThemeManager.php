<?php namespace Modules\Core\Foundation\Theme;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Str;

class ThemeManager
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Publish assets for the given theme
     * @param string $theme
     */
    public function publishAssetsFor($theme)
    {
        $theme = $this->find($theme);

        with(new AssetPublisher($theme))
            ->setFinder($this->getFinder())
            ->setRepository($this)
            ->publish();
    }

    /**
     * @param string $name
     * @return Theme|null
     */
    public function find($name)
    {
        foreach ($this->all() as $theme) {
            if ($theme->getLowerName() == strtolower($name)) {
                return $theme;
            }
        }
        return null;
    }

    /**
     * Return all available themes
     * @return array
     */
    public function all()
    {
        $themes = [];
        if (!$this->getFinder()->isDirectory('Themes')) {
            return $themes;
        }

        $directories = $this->getFinder()->directories('Themes');

        foreach ($directories as $theme) {
            if (!Str::startsWith($name = basename($theme), '.')) {
                $themes[$name] = new Theme($name, $theme);
            }
        }

        return $themes;
    }

    public function getAssetPath($theme)
    {
        return public_path($this->getConfig()->get('themify::themes_assets_path') . '/' . $theme);
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function getFinder()
    {
        return $this->app['files'];
    }

    /**
     * @return \Illuminate\Config\Repository
     */
    protected function getConfig()
    {
        return $this->app['config'];
    }
}
