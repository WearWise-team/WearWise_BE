<?php

namespace App\Services\Contracts;

interface IUserService
{
    public function getAllUsers();
    public function getUserById(int $id);
    public function createUser(array $data);
    public function updateUser(array $data, int $id);
    public function deleteUser(int $id);
}