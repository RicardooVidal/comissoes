<?php

namespace app\Repositories;

use Illuminate\Database\Eloquent\Model;

class AbstractRepository
{
    public $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function with($with)
    {
        $this->model = $this->model->with($with);
    }

    public function filter($filters)
    {
        $filters = explode(';', $filters);
        foreach($filters as $key => $condition) {
            $c = explode(':', $condition);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
        }
    }

    public function getResult()
    {
        return $this->model->get();
    }

    public function getResultPaginated($numberOfRegisters = 1)
    {
        return $this->model->paginate($numberOfRegisters);
    }
}