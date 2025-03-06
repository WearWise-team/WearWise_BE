<?php

namespace App\Repositories\Contracts;

interface ITryOnKRepository
{
    public function checkTaskResult(string $taskId, string $token);
}
