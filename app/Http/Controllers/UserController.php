<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DTO\UserRequestDTO;
use App\Services\Contracts\IUserService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $userService;
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }
    public function index()
    {
        try {
            $user = $this->userService->getAllUsers();
            return response()->json($user, status: 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching users', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequestDTO  $request)
    {
        $validateData = $request->validated();
        $validateData['password'] = bcrypt($validateData['password']);

        try {
            $user = $this->userService->createUser($validateData);
            return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while creating user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try {
            $user = $this->userService->getUserById($id);
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }
            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching user', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequestDTO $request, int $id)
    {
        try {
            $user = $this->userService->updateUser($id, $request->validated());
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update user', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->userService->deleteUser($id);
            return response()->json(['message' => 'User deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete user', 'message' => $e->getMessage()], 500);
        }
    }

    public function getUsersIsDeleted()
    {
        try {
            $user = $this->userService->getUsersIsDeleted();

            if ($user === null) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return response()->json($user, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while fetching user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function restoreUser($id) {
        try {
            $this->userService->restoreUser($id);
            return response()->json(['message' => 'User restored successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to restore user', 'message' => $e->getMessage()], 500);
        }
    }
}
