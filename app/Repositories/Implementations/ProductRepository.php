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
            ->leftJoin('images', 'images.product_id', '=', 'products.id')
            ->leftJoin('product_colors', 'product_colors.product_id', '=', 'products.id')
            ->leftJoin('colors', 'product_colors.color_id', '=', 'colors.id')
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
                'images.id as image_id',
                'images.url as image_url',
                'colors.id as color_id',
                'colors.name as color_name',
                'colors.code as color_code',
                'discounts.id as discount_id',
                'discounts.code as discount_code',
                'discounts.description as discount_description',
                'discount_assignments.start_date',
                'discount_assignments.end_date',
                'discount_assignments.percentage',
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
            'images' => [],
            'colors' => [],
            'discounts' => [],
            'reviews' => []
        ];

        $addedSizes = [];
        $addedImages = [];
        $addedColors = [];
        $addedDiscounts = [];
        $addedReviews = [];

        foreach ($productData as $row) {
            if ($row->review_id && !in_array($row->review_id, $addedReviews)) {
                $product['reviews'][] = [
                    'id' => $row->review_id,
                    'content' => $row->review_content,
                    'rating' => $row->rating,
                    'user' => [
                        'id' => $row->user_id,
                        'name' => $row->user_name
                    ]
                ];
                $addedReviews[] = $row->review_id;
            }

            if ($row->size_id && !in_array($row->size_id, $addedSizes)) {
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
                $addedSizes[] = $row->size_id;
            }

            if ($row->discount_id && !in_array($row->discount_id, $addedDiscounts)) {
                $product['discounts'][] = [
                    'id' => $row->discount_id,
                    'code' => $row->discount_code,
                    'description' => $row->discount_description,
                    'percentage' => $row->percentage,
                    'start_date' => $row->start_date,
                    'end_date' => $row->end_date
                ];
                $addedDiscounts[] = $row->discount_id;
            }

            if ($row->image_id && !in_array($row->image_id, $addedImages)) {
                $product['images'][] = [
                    'id' => $row->image_id,
                    'url' => $row->image_url
                ];
                $addedImages[] = $row->image_id;
            }

            if ($row->color_id && !in_array($row->color_id, $addedColors)) {
                $product['colors'][] = [
                    'id' => $row->color_id,
                    'name' => $row->color_name,
                    'code' => $row->color_code
                ];
                $addedColors[] = $row->color_id;
            }
        }

        return $product;
    }

    public function filterProduct(array $filters)
    {
        $query = $this->model->query();

        if (!empty($filters['color'])) {
            $query->whereHas('color', function ($q) use ($filters) {
                $q->where('name', $filters['color']);
            });
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
