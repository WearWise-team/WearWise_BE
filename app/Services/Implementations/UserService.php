<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use App\Services\Contracts\IUserService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserService implements IUserService
{
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    public function getUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(array $data)
    {
        if (!empty($data['avatar'])) {
            $uploadedFile = Cloudinary::upload($data['avatar']->getRealPath(), [
                'folder' => 'avatars'
            ]);
            $data['avatar'] = $uploadedFile->getSecurePath(); // Lấy URL ảnh
        }

        $data['password'] = bcrypt($data['password']);
        return $this->userRepository->create($data);
    }

    public function updateUser(int $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function getUsersIsDeleted() {
        return $this->userRepository->getUsersIsDeleted();
    }

    public function restoreUser(int $id) {
        return $this->userRepository->restoreUser($id);
    }
}