<?php

namespace App\Repositories\Contracts;

interface ISupplierRepository
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function deleteOrRestore(int $id);

    public function getSupplierByUserID($user_id);
}