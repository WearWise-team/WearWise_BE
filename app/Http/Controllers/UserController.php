<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\StoreUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $posts = $this->userService->getAllUsers();
        return response()->json($posts);
    }

    public function store(StoreUserRequest $request)
    {
        $validatedData = $request->validated();
        $post = $this->userService->createUser($validatedData);
        return response()->json($post, 201);
    }
}
