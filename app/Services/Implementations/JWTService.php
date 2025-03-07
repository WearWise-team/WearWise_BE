<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\IJWTRepository;
use App\Services\Contracts\IJWTService;
use Firebase\JWT\JWT;
use Exception;

class JWTService implements IJWTService
{
    protected IJWTRepository $repository;
    protected string $accessKey;
    protected string $secretKey;
    protected int $expireTime = 3600; // 60 phút

    public function __construct(IJWTRepository $repository)
    {
        $this->repository = $repository;
        $this->accessKey = env('API_ACCESS_KEY', 'your_access_key');
        $this->secretKey = env('API_SECRET_KEY', 'your_secret_key');
    }

    public function generateToken(): string
    {
        try {
            $issuedAt = time();
            $expireAt = $issuedAt + $this->expireTime; // Hết hạn sau 60 phút

            $payload = [
                'iss' => $this->accessKey, // Issuer
                'iat' => $issuedAt, // Thời điểm tạo token
                'nbf' => $issuedAt - 5, // Hiệu lực sau khi tạo 5s
                'exp' => $expireAt, // Thời gian hết hạn
            ];

            return JWT::encode($payload, $this->secretKey, 'HS256');
        } catch (Exception $e) {
            return response()->json(['error' => 'Token generation failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function tryOnClothesWithKling($humanImageBase64, $clothImageBase64)
    {
        $token = $this->generateToken(); // Lấy token mới
        // dd($humanImageBase64, $clothImageBase64, $token); 
        return $this->repository->sendRequestToKling($humanImageBase64, $clothImageBase64, $token);
    }
}
