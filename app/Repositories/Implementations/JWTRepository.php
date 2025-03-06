<?php

namespace App\Repositories\Implementations;

use App\Repositories\Contracts\IJWTRepository;

class JWTRepository implements IJWTRepository
{
    public function sendRequestToKling($humanImageBase64, $clothImageBase64, $token)
    {
        if (!$humanImageBase64 || !$clothImageBase64) {
            return response()->json(['error' => 'Upload failed'], 400);
        }

        $apiUrl = "https://api.klingai.com/v1/images/kolors-virtual-try-on";
        $apiKey = $token; // API key của KlingAI

        $postData = json_encode([
            'human_image' => $humanImageBase64,
            'cloth_image' => $clothImageBase64
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer " . $apiKey
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['error' => "cURL Error: " . $err], 500);
        }

        $responseData = json_decode($response, true);

        // Trả về kết quả cùng với token
        return response()->json([
            'token' => $token,
            'data' => $responseData
        ]);
    }
}
