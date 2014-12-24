<?php namespace Modules\Core\Repositories\Cache;

use Modules\Core\Repositories\BaseRepository;

abstract class BaseCacheDecorator implements BaseRepository
{
    /**
     * @var \Modules\Core\Repositories\BaseRepository
     */
    protected $repository;
    /**
     * @var \Illuminate\Cache\Repository
     */
    protected $cache;
    /**
     * @var string The entity name
     */
    protected $entityName;
    /**
     * @var string The application locale
     */
    protected $locale;

    public function __construct()
    {
        $this->cache = app('Illuminate\Cache\Repository');
        $this->cacheTime = app('Illuminate\Config\Repository')->get('cache.time', 60);
        $this->locale = app()->getLocale();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->cache->tags($this->entityName, 'global')->remember("{$this->entityName}.find.{$id}",
            $this->cacheTime, function () use ($id) {
                return $this->repository->find($id);
            });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->cache->tags($this->entityName, 'global')->remember("{$this->entityName}.all", $this->cacheTime,
            function () {
                return $this->repository->all();
            });
    }

    /**
     * Return all categories in the given language
     *
     * @param $lang
     * @return mixed
     */
    public function allTranslatedIn($lang)
    {
        return $this->cache
            ->tags($this->entityName, 'global')
            ->remember("{$this->locale}.{$this->entityName}.allTranslatedIn.{$lang}",
                function () use ($lang) {
                    return $this->repository->allTranslatedIn($lang);
                }
            );
    }

    /**
     * Create a resource
     *
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->create($data);
    }

    /**
     * Update a resource
     *
     * @param       $model
     * @param array $data
     * @return mixed
     */
    public function update($model, $data)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->update($model, $data);
    }

    /**
     * Destroy a resource
     *
     * @param $model
     * @return mixed
     */
    public function destroy($model)
    {
        $this->cache->tags($this->entityName)->flush();

        return $this->repository->destroy($model);
    }
}
