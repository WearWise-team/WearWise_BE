<?php
namespace App\Repositories\Contracts;

interface IAuthRepository
{
    public function register(array $data);
    public function login(array $data);
    public function logout();
    public function refresh();
    public function me();
}