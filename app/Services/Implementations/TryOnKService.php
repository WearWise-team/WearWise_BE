<?php

namespace App\Services\implements;

use App\Repositories\Contracts\ITryOnKRepository;

class TryOnKService
{
    protected ITryOnKRepository $tryOnKRepository;

    public function __construct(ITryOnKRepository $tryOnKRepository)
    {
        $this->tryOnKRepository = $tryOnKRepository;
    }

    public function getTryOnResult(string $taskId, string $token)
    {
        return $this->tryOnKRepository->checkTaskResult($taskId, $token);
    }
}
