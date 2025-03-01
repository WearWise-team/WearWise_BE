<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\IColorRepository;
use App\Services\Contracts\IColorService;

class ColorService implements IColorService
{
    protected $colorRepository;

    public function __construct(IColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    public function getAllColors()
    {
        return $this->colorRepository->getAll();
    }

    public function getColorById(int $id)
    {
        return $this->colorRepository->findById($id);
    }

    public function createColor(array $data)
    {
        return $this->colorRepository->create($data);
    }

    public function updateColor(array $data, int $id)
    {
        return $this->colorRepository->update($id, $data);
    }

    public function deleteColor(int $id)
    {
        return $this->colorRepository->delete($id);
    }
}
