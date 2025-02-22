<?php

namespace App\Http\Controllers;

use App\Services\Contracts\IProductService;
use App\Http\Requests\DTO\ProductRequestDTO;
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
    public function store(ProductRequestDTO $request)
    {
        $validated = $request->validated();

        $product = $this->productService->createProduct([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'image' => $validated['image'] ?? null,
            'quantity' => $validated['quantity'],
            'supplier_id' => $validated['supplier_id'],
        ]);

        return response()->json($product, 201);
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
}
