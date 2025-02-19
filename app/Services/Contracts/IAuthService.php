<?php

namespace App\Services\Contracts;

interface IAuthService
{
    public function register(array $data);
    public function login(array $data);
    public function logout();
    public function refresh();
    public function me();
}