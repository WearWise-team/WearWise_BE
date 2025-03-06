<?php

namespace App\Repositories\Contracts;

interface IProductRepository
{
    public function getAll();
    public function findById(int $id);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function search(string $name);
    public function GetProductDetails(int $id);
    public function filterProduct(array $filters);
    public function getProductWithColorAndSize();

}