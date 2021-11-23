<?php

namespace App\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PostRepositoryContract
{
    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function list(): Collection;

    public function postPaginateWithSearch(int $perPage, int $page, string $field, string $titleSearch, string $cat): LengthAwarePaginator;

    public function findOrFail(int $id);

    public function resolveModel();
}
