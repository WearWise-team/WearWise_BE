<?php

namespace App\Services\Contracts;

interface IProductService
{
    public function getAllProducts();
    public function getProductById(int $id);
    public function createProduct(array $data);
    public function updateProduct(int $id, array $data);
    public function deleteProduct(int $id);
    public function searchProductByName(string $name);
}