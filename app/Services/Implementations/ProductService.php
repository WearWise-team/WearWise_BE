<?php

namespace App\Services\Implementations;

use App\Models\Image;
use App\Repositories\Contracts\IProductRepository;
use App\Services\Contracts\IProductService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    public function createProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'quantity' => 'required|integer|min:1',
            'category' => 'required|string|max:255',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'colors' => 'required|array',
            'colors.*' => 'exists:colors,id',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'discounts' => 'required|array',
            'discounts.*' => 'exists:discounts,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Upload main image
            $mainImageUrl = null;
            if ($request->hasFile('main_image')) {
                $uploadedMainImage = Cloudinary::upload($request->file('main_image')->getRealPath(), [
                    'folder' => 'products/main',
                    'verify' => false
                ]);
                $mainImageUrl = $uploadedMainImage->getSecurePath();
            }

            // Create product
            $productData = [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'main_image' => $mainImageUrl,
                'quantity' => $request->quantity,
                'category' => $request->category,
                'supplier_id' => $request->supplier_id,
                'rating_avg' => $request->rating_avg ?? null,
            ];
            $product = $this->productRepository->create($productData);

            // Upload additional images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $uploadedImage = Cloudinary::upload($image->getRealPath(), [
                        'folder' => 'products/additional',
                        'verify' => false
                    ]);
                    $product->images()->create([
                        'url' => $uploadedImage->getSecurePath()
                    ]);
                }
            }

            // Attach colors
            if ($request->has('colors') && is_array($request->colors)) {
                $product->colors()->attach($request->colors);
            }

            // Attach sizes
            if ($request->has('sizes') && is_array($request->sizes)) {
                $product->sizes()->attach($request->sizes);
            }

            // Attach discount
            if ($request->has('discounts') && is_array($request->discounts)) {
                $product->discounts()->attach($request->discounts);
            }

            DB::commit();

            // Load relationships
            $product->load(['images', 'colors', 'sizes', 'discounts']);

            return response()->json([
                'success' => true,
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product',
                'error' => $e->getMessage()
            ], 500);
        }
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
    
    public function filterProduct(array $filters)
    {
        return $this->productRepository->filterProduct($filters);
    }

    public function getProductWithColorAndSize()
    {
        return $this->productRepository->getProductWithColorAndSize();
    }

    public function getProductBySupplierID(int $supplierId)
    {
        return $this->productRepository->getProductBySupplierID($supplierId);
    }
}