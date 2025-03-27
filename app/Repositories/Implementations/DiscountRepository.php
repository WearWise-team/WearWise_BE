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
        $discount = $this->model->find($id);
        if (!$discount) {
            return null;
        }

        $discount->update($data);
        
        return $discount;
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }
}