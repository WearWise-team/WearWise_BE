<?php

namespace App\Repositories\Implementations;

use App\Models\Color;
use App\Repositories\Contracts\IColorRepository;

class ColorRepository implements IColorRepository
{
    protected $model;

    public function __construct(Color $model)
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
        $color = $this->model->find($id);
        if (!$color) {
            return null;
        }
        $color->update($data);
        return $color;
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }
}
