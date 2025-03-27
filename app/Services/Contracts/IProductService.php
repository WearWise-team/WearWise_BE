<?php

namespace App\Services\Contracts;

use Illuminate\Http\Request;


interface IProductService
{
    public function getAllProducts();
    public function getProductById(int $id);
    public function createProduct(Request $request);
    public function updateProduct(int $id, array $data);
    public function deleteProduct(int $id);
    public function searchProductByName(string $name);
    public function getProductDetails(int $id);
    public function filterProduct(array $filters);
    public function getProductWithColorAndSize();
    public function getProductBySupplierID(int $supplierId);
}