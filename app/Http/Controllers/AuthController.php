<?php

namespace App\Http\Controllers;

use App\Http\Requests\DTO\UserRequestDTO;
use App\Services\Contracts\IUserService;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function signup(UserRequestDTO $userRequestDTO): JsonResponse
    {
        try {
            $validatedData = $userRequestDTO->validated();
            // $validatedData['password'] = bcrypt($validatedData['password']);

            $user = $this->userService->createUser($validatedData);

            return response()->json([
                'message' => 'User created successfully',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => 'error',
                'errors' => $validator->errors()->all()
            ], 400);
        }

        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password')))
            {
                return response()->json([
                    'response' => 'error',
                    'message' => 'Invalid email or password'
                ], 401);
            }

            $user = auth()->user();

            return response()->json([
                'response' => 'success',
                'result' => [
                    'token' => $this->respondWithToken($token),
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'avatar' => $user->avatar,
                        'phone' => $user->phone,
                        'role' => $user->role
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'response' => 'error',
                'message' => 'Something went wrong, please try again'
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Kiểm tra người dùng đã đăng nhập chưa
            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Successfully logged out'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to logout, token might be invalid or expired'
            ], 400);
        }
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}