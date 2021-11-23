<?php

namespace App\Repositories\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class AbstractRepository
{
    protected $model;

    public function __construct()
    {
        $this->model->resolveModel();
    }

    /**
     * Create Registers
     */
    public function create(array $data)
    {
        return $this->model->fill($data)->save();
    }

    /**
     * List Registers
     */
    public function list(): Collection
    {
        return $this->model->all();
    }

    /**
     * Paginate Registers and Search
     */
    public function paginateWithSearch(int $perPage, string $field, string $nameSearch): LengthAwarePaginator
    {
        $mainQuery = $this->model->when($nameSearch, function ($query) use ($nameSearch, $field) {
            $query->where($field, "like", "%$nameSearch%");
        });

        return $mainQuery->paginate($perPage);
    }

    /**
     * Find a register by id
     */
    public function findOrFail(int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update a register
     */
    public function update(int $id, array $data)
    {

        $model = $this->model->findOrFail($id);

        $model->fill($data);

        return $model->save();
    }

    /**
     * Delete a register
     */
    public function delete(int $id)
    {
        $model = $this->model->findOrFail($id);

        return $model->delete();
    }

    /**
     * Get Name of the Table
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    public function resolveModel()
    {
        return app($this->model);
    }
}
