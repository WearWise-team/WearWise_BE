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
    
}
?>