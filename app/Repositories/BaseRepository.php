<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;   

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(Array $columns = ['*'])
    {
        return $this->model->get($columns);
    }

    public function create(array $data, bool $force = true)
    {
        $model = $force? $this->model->forceCreate($data) : $this->model->create($data);
        return $model;
    }

    public function update(array $data, int $id, bool $force = true, bool $withTrashed = false)
    {
        $model = $withTrashed? $this->model->withTrashed()->find($id) : $this->find($id);

        $force? $model->forceFill($data)->save() : $model->update($data);

        return $model->fresh();
    }

    public function updateMultiple(Array $data, Array $ids)
    {
        $this->model->whereIn('id', $ids)->update($data);
    }

    public function delete(int $id)
    {
        return $this->find($id, ['*'], true)->delete();
    }

    public function find(int $id, Array $columns = ['*'], bool $fail = true)
    {
        $method = $fail? 'findOrFail' : 'find';
        $result = $this->model->{$method}($id, $columns);

        return $result;
    }

    public function findBy(string $field, string $value, Array $columns = array('*'))
    {
        return $this->model->where($field, $value)->first($columns);
    }
}