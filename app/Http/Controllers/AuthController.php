<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DTO\UserRequestDTO;
use App\Services\Contracts\IUserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(IUserService $userService)
    {
        Auth::shouldUse('api');
        $this->userService = $userService;
    }
    protected $userService; 
    public function signup(UserRequestDTO $userRequestDTO)
    {
        $validateData = $userRequestDTO->validated();
        $validateData['password'] = bcrypt($validateData['password']);

        try {
            $user = $this->userService->createUser($validateData);
            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while creating user', 'error' => $e->getMessage()], 500);
        } 
    }
    
}