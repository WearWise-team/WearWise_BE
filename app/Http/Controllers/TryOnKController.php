<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ITryOnKService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TryOnKController extends Controller
{
    protected ITryOnKService $tryOnKService;

    public function __construct(ITryOnKService $tryOnKService)
    {
        $this->tryOnKService = $tryOnKService;
    }

    public function getTryOnResult(string $taskId, Request $request): JsonResponse
    {
        $token = $request->get('token');
        $result = $this->tryOnKService->getTryOnResult($taskId, $token);

        return response()->json($result, isset($result['error']) ? 400 : 200);
    }
}
