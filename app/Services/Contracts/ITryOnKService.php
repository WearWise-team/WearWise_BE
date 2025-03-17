<?php

namespace App\Services\Contracts;

interface ITryOnKService
{
    public function getTryOnResult(string $taskId, string $token);
}
