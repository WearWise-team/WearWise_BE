<?php

namespace App\Repositories\Implementations;

use App\Models\Size;
use App\Repositories\Contracts\ISizeRepository;

class SizeRepository implements ISizeRepository
{
    protected $model;

    public function __construct(Size $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $post = $this->model->find($id);
        return $post ? $post->update($data) : null;
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }
    
}
?>