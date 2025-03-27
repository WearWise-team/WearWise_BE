<?php

namespace App\Services\Contracts;

interface IUserService
{
    public function getAllUsers();
    public function getUserById(int $id);
    public function createUser(array $data);
    public function updateUser( int $id, array $data);
    public function deleteUser(int $id);
    public function getUsersIsDeleted();
    public function restoreUser(int $id);
}