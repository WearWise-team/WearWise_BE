<?php

namespace App\Services\Implementations; 

use App\Repositories\Contracts\IProductRepository;
use App\Services\Contracts\IProductService;

class ProductService implements IProductService
{
    protected $productRepository;

    public function __construct(IProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->getAll();
    }

    public function getProductById(int $id)
    {
        return $this->productRepository->findById($id);
    }

    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function updateProduct(int $id, array $data)
    {
        return $this->productRepository->update($id, $data);
    }

    public function deleteProduct(int $id)
    {
        return $this->productRepository->delete($id);
    }

    public function searchProductByName(string $name)
    {
        return $this->productRepository->search($name);
    }

    public function getProductDetails(int $id)
    {
        return $this->productRepository->getProductDetails($id);
    }
}