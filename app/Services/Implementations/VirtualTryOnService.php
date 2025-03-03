<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\IVirtualTryOnRepository;
use App\Services\Contracts\IVirtualTryOnService;

class VirtualTryOnService implements IVirtualTryOnService
{
    protected $repository;

    public function __construct(IVirtualTryOnRepository $repository)
    {
        $this->repository = $repository;
    }

    public function tryOnClothes($personImage, $clothImage)
    {
        return $this->repository->sendRequest($personImage, $clothImage);
    }
}