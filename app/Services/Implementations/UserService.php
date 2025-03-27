<?php

namespace App\Services\Implementations;

use App\Models\Supplier;
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
        $data['avatar'] = is_string($data['avatar'])
            ? $data['avatar']
            : Cloudinary::upload($data['avatar']->getRealPath(), ['folder' => 'avatars', 'verify' => false])->getSecurePath();

        $data['password'] = bcrypt($data['password']);
        $user =  $this->userRepository->create($data);

        if ($user->role === 'supplier') {
            Supplier::create([
                'user_id' => $user->id,
                'name' => 'Wearwise Shop', // 🔥 Luôn luôn là "Wearwise Shop"
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'avatar' => $user->avatar ?? null
            ]);
        }

        return $user;
    }

    public function updateUser(int $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }

    public function deleteUser(int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function getUsersIsDeleted()
    {
        return $this->userRepository->getUsersIsDeleted();
    }

    public function restoreUser(int $id)
    {
        return $this->userRepository->restoreUser($id);
    }
}