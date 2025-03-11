<?php

namespace App\Http\Controllers;

use App\Http\Requests\DTO\ProductRequestDTO;
use App\Services\Contracts\IProductService;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
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
    public function update($id, ProductRequestDTO $request)
    {
        $validated = $request->validated();

        $product = $this->productService->updateProduct($id, [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $validated['image'] ?? null,
            'quantity' => $validated['quantity'],
        ]);

        return response()->json($product);
    }

    /**
     * Remove the specified product from the database.
     */
    public function destroy($id)
    {
        $this->productService->deleteProduct($id);

        return response()->json(null, 204);
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
}
    