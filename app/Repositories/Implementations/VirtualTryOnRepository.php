<?php

namespace App\Repositories\Implementations;

use App\Repositories\Contracts\IVirtualTryOnRepository;

class VirtualTryOnRepository implements IVirtualTryOnRepository
{
    protected $apiUrl;
    protected $apiKey;
    
    public function __construct()
    {
        $this->apiUrl = "https://virtual-try-on2.p.rapidapi.com/clothes-virtual-tryon";
        $this->apiKey = env('RAPIDAPI_KEY');
    }

    public function sendRequest($personImage, $clothImage)
    {
        if (!$personImage || !$clothImage) {
            return response()->json(['error' => 'File upload failed'], 400);
        }

        // Kiểm tra và lấy đường dẫn file tạm thời
        $personImagePath = $personImage->getRealPath();
        $clothImagePath = $clothImage->getRealPath();

        // Kiểm tra xem tệp có tồn tại không
        if (!file_exists($personImagePath) || !file_exists($clothImagePath)) {
            return response()->json(['error' => 'One or both files are missing'], 400);
        }

        // Chuẩn bị dữ liệu cho cURL
        $postFields = [
            'personImage' => new \CURLFile($personImagePath, $personImage->getMimeType(), $personImage->getClientOriginalName()),
            'clothImage' => new \CURLFile($clothImagePath, $clothImage->getMimeType(), $clothImage->getClientOriginalName()),
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_POST => true,
            CURLOPT_SSL_VERIFYPEER => false, 
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                "x-rapidapi-host: virtual-try-on2.p.rapidapi.com",
                "x-rapidapi-key: $this->apiKey",
                // "Content-Type: multipart/form-data",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response()->json(['error' => "cURL Error: " . $err], 500);
        }

        return response()->json(json_decode($response, true));
    }

}