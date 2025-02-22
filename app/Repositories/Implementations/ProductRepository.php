<?php
namespace App\Repositories\Implementations;

use App\Models\Product;
use App\Repositories\Contracts\IProductRepository;

class ProductRepository implements IProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById(int $id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $post = $this->model->find($id);
        return $post ? $post->update($data) : null;
    }

    public function delete(int $id)
    {
        return $this->model->destroy($id);
    }

    public function search(string $name)
    {
        return Product::where('name', 'LIKE', "%$name%")->get();
    }

    public function filterProduct(array $filters)
    {
        $query = $this->model->query();

        if (!empty($filters['color'])) {
            $query->where('color', $filters['color']);
        }

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        return $query->get();
    }

}
?>