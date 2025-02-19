<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Repositories\Contracts\IAuthRepository;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements IAuthRepository
{
    public function register(array $data)
    {
        return User::create($data);
    }

    public function login(array $data)
    {
        return Auth::attempt($data) ? Auth::user() : null;
    }
    
    public function logout()
    {
        Auth::logout();
    }

    public function refresh()
    {
        return Auth::refresh();
    }

    public function me()
    {
        return Auth::user();
    }
}