<?php

namespace App\Repositories\Implementations;

use App\Models\Product;
use App\Repositories\Contracts\IProductRepository;
use Illuminate\Support\Facades\DB;

class ProductRepository implements IProductRepository
{
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->with(['reviews', 'discounts' => function ($query) {
            $query->latest()->take(1);
        }])->get();
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

    public function getProductDetails(int $id)
    {
        $productData = DB::table('products')
            ->leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
            ->leftJoin('users', 'reviews.user_id', '=', 'users.id')
            ->leftJoin('size', 'size.product_id', '=', 'products.id')
            ->leftJoin('suppliers', 'products.supplier_id', '=', 'suppliers.id')
            ->leftJoin('discount_assignments', 'products.id', '=', 'discount_assignments.product_id')
            ->leftJoin('discounts', 'discount_assignments.discount_id', '=', 'discounts.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'products.description',
                'products.price',
                'products.quantity',
                'products.image',
                'suppliers.id as supplier_id',
                'suppliers.name as supplier_name',
                'suppliers.address as supplier_address',
                'suppliers.avatar',
                'suppliers.phone',
                'size.id as size_id',
                'size.shirt_size',
                'size.pant_size',
                'size.minimun_weight',
                'size.maximun_weight',
                'size.minimun_height',
                'size.maximun_height',
                'size.target_audience',
                'discounts.id as discount_id',
                'discounts.code as discount_code',
                'discounts.description as discount_description',
                'discounts.start_date',
                'discounts.end_date',
                'discounts.percentage',
                'reviews.id as review_id',
                'reviews.content as review_content',
                'reviews.rating',
                'users.id as user_id',
                'users.name as user_name'
            )
            ->where('products.id', $id)
            ->get();

        if ($productData->isEmpty()) {
            return null;
        }

        // Nhóm dữ liệu thành JSON có cấu trúc theo từng bảng
        $product = [
            'id' => $productData[0]->product_id,
            'name' => $productData[0]->product_name,
            'description' => $productData[0]->description,
            'price' => $productData[0]->price,
            'quantity' => $productData[0]->quantity,
            'image' => $productData[0]->image,
            'supplier' => [
                'id' => $productData[0]->supplier_id,
                'name' => $productData[0]->supplier_name,
                'avatar' => $productData[0]->avatar,
                'phone' => $productData[0]->phone,
                'address' => $productData[0]->supplier_address,
            ],
            'sizes' => [],
            'discounts' => [],
            'reviews' => []
        ];

        foreach ($productData as $row) {
            if ($row->review_id) {
                $product['reviews'][] = [
                    'id' => $row->review_id,
                    'content' => $row->review_content,
                    'rating' => $row->rating,
                    'user' => [
                        'id' => $row->user_id,
                        'name' => $row->user_name
                    ]
                ];
            }

            if ($row->size_id) {
                $product['sizes'][] = [
                    'id' => $row->size_id,
                    'shirt_size' => $row->shirt_size,
                    'pant_size' => $row->pant_size,
                    'minimun_weight' => $row->minimun_weight,
                    'maximun_weight' => $row->maximun_weight,
                    'minimun_height' => $row->minimun_height,
                    'maximun_height' => $row->maximun_height,
                    'target_audience' => $row->target_audience
                ];
            }

            if ($row->discount_id) {
                $product['discounts'][] = [
                    'id' => $row->discount_id,
                    'code' => $row->discount_code,
                    'description' => $row->discount_description,
                    'start_date' => $row->start_date,
                    'end_date' => $row->end_date,
                    'percentage' => $row->percentage
                ];
            }
        }

        return $product;
    }
}
