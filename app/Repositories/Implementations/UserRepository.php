<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryContract;
use App\Repositories\Implementations\AbstractRepository;


class UserRepository extends AbstractRepository implements UserRepositoryContract
{
    protected $model = User::class;

    public function import(array $users)
    {
        return $this->model->insert($users);
    }

    public function export()
    {
        return $this->model->all();
    }

    public function findValue(string $column, string $value)
    {
        return $this->model->where($column, $value)->first();
    }
}
