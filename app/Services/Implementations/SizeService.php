<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\ISizeRepository;

class SizeService
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

    public function getSizeById(int $id)
    {
        return $this->sizeRepository->findById($id);
    }

    public function createSize(array $data)
    {
        return $this->sizeRepository->create($data);
    }

    public function updateSize(int $id, array $data)
    {
        return $this->sizeRepository->update($id, $data);
    }

    public function deleteSize(int $id)
    {
        return $this->sizeRepository->delete($id);
    }
}