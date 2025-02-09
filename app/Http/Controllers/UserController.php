<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required',
            'password' => 'required',
        ]);

        $post = $this->userService->createUser($data);
        return response()->json($post, 201);
    }
}
