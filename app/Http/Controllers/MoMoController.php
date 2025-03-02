<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MoMoController extends Controller
{
    public function createPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderId = $request->orderId . ":" . time();
        $requestId = time() . "";
        $amount = $request->amount;
        $orderInfo = "MoMo";
        $redirectUrl = 'http://localhost:3000/profile';
        $ipnUrl = 'http://localhost:8000/api/momo/ipn';
        $requestType = "captureWallet";
        $extraData = "";

        // Tạo chữ ký bảo mật (HMAC SHA256)
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // Chuẩn bị dữ liệu gửi đến MoMo
        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "WearWise",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        // Gửi request đến MoMo
        $response = Http::withOptions([
            'verify' => false
        ])->post($endpoint, $data);

        return response()->json($response->json());
    }
}
