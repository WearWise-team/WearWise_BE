<?php

namespace App\Services\Contracts;

interface IProductService
{
    public function getAllProducts();
    public function getProductById(int $id);
    public function createProduct(array $data);
    public function updateProduct(array $data, int $id);
    public function deleteProduct(int $id);
}