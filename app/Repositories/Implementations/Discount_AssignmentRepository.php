<?php

namespace App\Repositories\Implementations;

use App\Models\Discount_Assignment;
use App\Repositories\Contracts\IDiscount_AssignmentRepository;

class Discount_AssignmentRepository implements IDiscount_AssignmentRepository
{
    protected $model;

    public function __construct(Discount_Assignment $model)
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