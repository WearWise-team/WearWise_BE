<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\ITryOnKRepository;
use App\Services\Contracts\ITryOnKService;

class TryOnKService implements ITryOnKService
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
