<?php

namespace App\Repositories\Implementations;

use App\Models\Discount;
use App\Repositories\Contracts\IDiscountRepository;

class DiscountRepository implements IDiscountRepository
{
    protected $model;

    public function __construct(Discount $model)
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