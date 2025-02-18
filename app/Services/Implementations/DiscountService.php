<?php

namespace App\Services\Implementations;
use App\Repositories\Contracts\IDiscountRepository;

class DiscountService
{
    protected $discountRepository;

    public function __construct(IDiscountRepository $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    public function getAllDiscounts()
    {
        return $this->discountRepository->getAll();
    }

    public function getDiscountById(int $id)
    {
        return $this->discountRepository->findById($id);
    }

    public function createDiscount(array $data)
    {
        return $this->discountRepository->create($data);
    }

    public function updateDiscount(int $id, array $data)
    {
        return $this->discountRepository->update($id, $data);
    }

    public function deleteDiscount(int $id)
    {
        return $this->discountRepository->delete($id);
    }
}