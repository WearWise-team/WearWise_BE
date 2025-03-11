<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\ISupplierRepository;
use App\Services\Contracts\ISupplierService;

class SupplierService implements ISupplierService
{
    protected $supplierRepository;

    public function __construct(ISupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    public function getAllSuppliers()
    {
        return $this->supplierRepository->getAll();
    }

    public function getSupplierById(int $id)
    {
        return $this->supplierRepository->findById($id);
    }

    public function createSupplier(array $data)
    {
        return $this->supplierRepository->create($data);
    }

    public function updateSupplier(array $data, int $id)
    {
        return $this->supplierRepository->update($id, $data);
    }

    public function deleteSupplier(int $id)
    {
        return $this->supplierRepository->delete($id);
    }

    public function getSupplierByUserID($user_id){
        return $this->supplierRepository->getSupplierByUserID($user_id);
    }
}