<?php

namespace App\Services\Implementations;
use App\Repositories\Contracts\IDiscountRepository;
use App\Services\Contracts\IDiscountService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class DiscountService implements IDiscountService
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

    public function updateDiscount($id, array $data)
    {
        $validator = Validator::make($data, [
            'code'        => 'required|string|max:50',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'percentage'  => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Update Discount
        $discount = $this->discountRepository->update($id, $data);
        if (!$discount) {
            return null;
        }

        return $discount;
    }

    public function deleteDiscount(int $id)
    {
        return $this->discountRepository->delete($id);
    }
}