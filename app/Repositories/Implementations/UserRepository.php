<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;

class UserRepository implements IUserRepository
{
    protected $model;

    public function __construct(User $model)
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

    public function getUsersIsDeleted()
    {
        $user = $this->model->onlyTrashed()->get();

        if (!$user) {
            return null;
        }
        return $user;
    }

    public function restoreUser($id)
    {
        $user = $this->model->onlyTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found or not deleted'], 404);
        }

        $user->restore();

        return response()->json(['message' => 'User restored successfully'], 200);
    }
}
