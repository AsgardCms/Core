<?php namespace Modules\Core\Composers;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\View\View;

class ApplicationVersionViewComposer
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Repository
     */
    private $cache;

    public function __construct(Filesystem $filesystem, Repository $cache)
    {
        $this->filesystem = $filesystem;
        $this->cache = $cache;
    }

    public function compose(View $view)
    {
        $view->with('version', $this->getAppVersion());
    }

    /**
     * @return string
     */
    private function getAppVersion()
    {
        $composerFile = $this->getComposerFile();

        return isset($composerFile->version) ? $composerFile->version : '1.0';
    }

    /**
     * Get the decoded contents from the main composer.json file
     * @return object
     */
    private function getComposerFile()
    {
        $composerFile = $this->cache->remember('app.version', 1440, function () {
            return $this->filesystem->get('composer.json');
        });

        return json_decode($composerFile);
    }
}
