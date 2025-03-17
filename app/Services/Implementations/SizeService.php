<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\ISizeRepository;
use App\Services\Contracts\ISizeService;

class SizeService implements ISizeService
{
    protected $sizeRepository;

    public function __construct(ISizeRepository $sizeRepository)
    {
        $this->sizeRepository = $sizeRepository;
    }

    public function getAllSizes()
    {
        return $this->sizeRepository->getAll();
    }
}