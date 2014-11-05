<?php namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface CoreRepository
 * @package Modules\Core\Repositories
 */
interface BaseRepository
{
    /**
     * @param int $id
     * @return Model $model
     */
    public function find($id);

    /**
     * Return a collection of all elements of the resource
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all();

    /**
     * Create a resource
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * Update a resource
     * @param Model $model
     * @param array $data
     * @return mixed
     */
    public function update(Model $model, $data);

    /**
     * Destroy a resource
     * @param Model $model
     * @return mixed
     */
    public function destroy(Model $model);
}
