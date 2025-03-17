<?php

namespace App\Repositories\Implementations;

use App\Repositories\Contracts\ITryOnKRepository;

class TryOnKRepository implements ITryOnKRepository
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = env('KLINGAI_API_URL', 'https://api.klingai.com/v1/images/kolors-virtual-try-on');
    }

    public function checkTaskResult(string $taskId, string $token)
    {
        $queryUrl = "$this->apiUrl/$taskId";

        for ($i = 0; $i < 24; $i++) {
            sleep(5);

            // Gọi API bằng cURL
            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL => $queryUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer " . $token,
                    "Content-Type: application/json",
                ],
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                return [
                    'code' => -1,
                    'message' => "cURL Error: $err",
                    'request_id' => null,
                    'data' => null
                ];
            }

            $result = json_decode($response, true);

            // Nếu response không phải JSON hợp lệ
            if (!is_array($result)) {
                // return [
                //     'code' => -1,
                //     'message' => "Invalid response format",
                //     'request_id' => null,
                //     'data' => null
                // ];
                return $response;
            }

            // Kiểm tra xem API có trả về code = 0 không (thành công)
            if (!isset($result['code']) || $result['code'] !== 0) {
                return [
                    'code' => $result['code'] ?? -1,
                    'message' => $result['message'] ?? 'Unknown error',
                    'request_id' => $result['request_id'] ?? null,
                    'data' => null
                ];
            }

            $taskStatus = $result['data']['task_status'] ?? '';
            $taskStatusMsg = $result['data']['task_status_msg'] ?? 'No status message';
            $createdAt = $result['data']['created_at'] ?? null;
            $updatedAt = $result['data']['updated_at'] ?? null;

            if ($taskStatus === 'succeed') {
                return [
                    'code' => 0,
                    'message' => $result['message'] ?? 'Success',
                    'request_id' => $result['request_id'] ?? null,
                    'data' => [
                        'task_id' => $result['data']['task_id'] ?? null,
                        'task_status' => $taskStatus,
                        'task_status_msg' => $result['data']['task_status_msg'] ?? null,
                        'created_at' => $result['data']['created_at'] ?? null,
                        'updated_at' => $result['data']['updated_at'] ?? null,
                        'task_result' => [
                            'images' => array_map(function ($image) {
                                return [
                                    'index' => $image['index'] ?? null,
                                    'url' => $image['url'] ?? null
                                ];
                            }, $result['data']['task_result']['images'] ?? [])
                        ]
                    ]
                ];
            }

            if ($taskStatus === 'failed') {
                return [
                    'code' => -2,
                    'message' => 'Task failed',
                    'request_id' => $result['request_id'] ?? null,
                    'data' => [
                        'task_id' => $result['data']['task_id'] ?? null,
                        'task_status' => $taskStatus,
                        'task_status_msg' => $taskStatusMsg,
                        'created_at' => $createdAt,
                        'updated_at' => $updatedAt
                    ]
                ];
            }
        }

        return [
            'code' => -3,
            'message' => 'Request timed out after 120 seconds',
            'request_id' => null,
            'data' => null
        ];
    }
}
