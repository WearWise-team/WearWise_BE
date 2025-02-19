<?php

namespace App\Services\Implementations;
use App\Repositories\Contracts\IAuthRepository;

class AuthService
{
    private $authRepository;

    public function __construct(IAuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data)
    {
        return $this->authRepository->register($data);
    }

    public function login(array $data)
    {
        return $this->authRepository->login($data);
    }

    public function logout()
    {
        return $this->authRepository->logout();
    }

    public function refresh()
    {
        return $this->authRepository->refresh();
    }

    public function me()
    {
        return $this->authRepository->me();
    }
}