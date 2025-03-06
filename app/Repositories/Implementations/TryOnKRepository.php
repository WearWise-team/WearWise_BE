<?php

namespace App\Repositories;

use App\Repositories\Contracts\ITryOnKRepository;
use Illuminate\Support\Facades\Http;

class TryOnKRepository implements ITryOnKRepository
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('KLINGAI_API_URL', 'https://api.klingai.com/v1images/kolors-virtual-try-on');
    }

    public function checkTaskResult(string $taskId, string $token): array
    {
        $queryUrl = "$this->apiUrl/$taskId";

        for ($i = 0; $i < 24; $i++) {
            sleep(5);
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->get($queryUrl);

            $result = $response->json();

            if ($result['code'] !== 0) {
                return ['error' => $result['message'] ?? 'Unknown error'];
            }

            $taskStatus = $result['data']['task_status'] ?? '';

            if ($taskStatus === 'succeed') {
                return ['image_url' => $result['data']['task_result']['images'][0]['url'] ?? null];
            }

            if ($taskStatus === 'failed') {
                return ['error' => $result['data']['task_status_msg'] ?? 'Unknown failure reason'];
            }
        }

        return ['error' => 'Request timed out after 120 seconds'];
    }
}
