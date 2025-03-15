<?php

namespace App\Repositories\Implementations;

use App\Models\Supplier;
use App\Repositories\Contracts\ISupplierRepository;

class SupplierRepository implements ISupplierRepository
{
    protected $model;

    public function __construct(Supplier $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->withTrashed()->get();
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

    public function deleteOrRestore(int $id)
    {
        $model = $this->model->withTrashed()->find($id);
        if (!$model) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
        if ($model->trashed()) {
            $model->restore();
            return response()->json(['message' => 'Supplier restored successfully'], 200);
        }
        $model->delete();
        return response()->json(['message' => 'Supplier deleted successfully'], 200);
    }

    public function getSupplierByUserID($user_id)
    {
        return $this->model::where('user_id', $user_id)->first();
    }
}