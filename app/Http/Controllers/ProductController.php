<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Services\Contracts\IProductService;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected $productService;

    // Inject IProductService vào controller
    public function __construct(IProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the products.
     */
    public function index()
    {
        $products = $this->productService->getAllProducts();
        return response()->json($products);
    }

    /**
     * Store a newly created product in the database.
     */
    public function store(Request $request)
    {
        return $this->productService->createProduct($request);
    }
    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = $this->productService->getProductById((int) $id);
        return response()->json($product);
    }


    /**
     * Update the specified product in the database.
     */
    public function updateProduct(Request $request, $id)
    {
        return $this->productService->updateProduct($id, $request->all());
    }
    /**
     * Remove the specified product from the database.
     */
    public function destroy($id)
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $this->productService->deleteProduct($id);

        return response()->json([
            'message' => 'Product deleted successfully',
            'deleted_product' => $product
        ], 200);
    }

    public function searchProductByName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2'
        ]);

        $products = $this->productService->searchProductByName($request->input('name'));

        return response()->json($products);
    }

    public function getProductDetails($id)
    {
        $product = $this->productService->getProductDetails($id);
        return response()->json($product);
    }
    public function filterProduct(Request $request)
    {
        $filters = $request->all();

        $products = $this->productService->filterProduct($filters);

        return response()->json($products);
    }

    public function getProductWithColorAndSize()
    {
        $products = $this->productService->getProductWithColorAndSize();
        return response()->json($products);
    }

    public function getProductBySupplierID(int $supplierId)
    {
        $products = $this->productService->getProductBySupplierID($supplierId);
        return response()->json($products);
    }

    public function restoreProduct($id)
    {
        // Tìm sản phẩm đã bị xóa mềm (soft deleted)
        $product = Product::onlyTrashed()->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found or not deleted'], 404);
        }
        $product->restore();
        DB::table('product_sizes')
            ->where('product_id', $id)
            ->update(['deleted_at' => null]);

        DB::table('product_colors')
            ->where('product_id', $id)
            ->update(['deleted_at' => null]);

        DB::table('discount_assignments')
            ->where('product_id', $id)
            ->update(['deleted_at' => null]);

        $product->images()->withTrashed()->restore();

        return response()->json(['message' => 'Product and related data restored successfully', 'product' => $product]);
    }
}
    