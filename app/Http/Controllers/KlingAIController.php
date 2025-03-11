<?php

namespace App\Http\Controllers;

use App\Services\Contracts\IJWTService;
use Illuminate\Http\Request;

class KlingAIController extends Controller
{
    protected IJWTService $jwtService;

    public function __construct(IJWTService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function generateToken() {
       return $this->jwtService->generateToken();
    }

    public function tryOnClothesWithKling(Request $request)
    {
        try {
            // Lấy chuỗi Base64 từ request
            $humanImageBase64 = $request->input('human_image');
            $clothImageBase64 = $request->input('cloth_image');

            if (empty($humanImageBase64) || empty($clothImageBase64)) {
                return response()->json(['error' => 'Upload failed - No valid string (base64)'], 400);
            }

            // dd($humanImageBase64, $clothImageBase64);

            // Gọi service thử đồ với Base64
            $result = $this->jwtService->tryOnClothesWithKling($humanImageBase64, $clothImageBase64);

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
