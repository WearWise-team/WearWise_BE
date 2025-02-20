<?php

namespace App\Services\Contracts;

interface ISupplierService
{
    public function getAllSuppliers();
    public function getSupplierById(int $id);
    public function createSupplier(array $data);
    public function updateSupplier(array $data, int $id);
    public function deleteSupplier(int $id);
}
?>