<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UserRepositoryContract
{
    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function list(): Collection;

    public function paginateWithSearch(int $perPage, string $field, string $nameSearch): LengthAwarePaginator;

    public function findOrFail(int $id);

    public function resolveModel();

    public function getTable();

    public function import(array $users);

    public function export();

    public function findValue(string $column, string $value);
}
