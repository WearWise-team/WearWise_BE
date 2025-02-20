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
        return $this->model::where('name', 'LIKE', "%{$name}%") -> get();
    }
    
}
?>