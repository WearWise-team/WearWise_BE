<?php

namespace App\Services\Contracts;

interface IDiscountService
{
    public function getAllDiscounts();
    public function getDiscountById(int $id);
    public function createDiscount(array $data);
    public function updateDiscount(int $id, array $data);
    public function deleteDiscount(int $id);
}
?>