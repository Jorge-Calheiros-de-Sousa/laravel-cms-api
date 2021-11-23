<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoryRepositoryContract
{
    public function create(array $data);

    public function list(bool $useCache = true, int $limit = 5): Collection;

    public function paginateWithSearch(int $perPage, string $field, string $titleSearch): LengthAwarePaginator;

    public function update(int $id, array $data);

    public function findOrFail(int $id);

    public function delete(int $id);

    public function resolveModel();
}
