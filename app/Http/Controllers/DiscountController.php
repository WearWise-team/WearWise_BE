<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountRequest;
use App\Services\Contracts\IDiscountService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(IDiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function index()
    {
        $discounts = $this->discountService->getAllDiscounts();
        return response()->json($discounts);
    }

    public function store(DiscountRequest $request) {
        $discount = $this->discountService->createDiscount($request->validated());

        return response()->json([
            'message' => 'Discount created successfully!',
            'data' => $discount
        ], 201);
    }

    public function update(Request $request, $id)
    {
        try {
            $discount = $this->discountService->updateDiscount($id, $request->all());

            if (!$discount) {
                return response()->json(['message' => 'Discount not found'], 404);
            }

            return response()->json(['message' => 'Discount updated successfully', 'data' => $discount], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }
    }

    public function delete($id)
    {
        $deleted = $this->discountService->deleteDiscount($id);

        if (!$deleted) {
            return response()->json(['message' => 'Discount not found'], 404);
        }

        return response()->json(['message' => 'Discount deleted successfully'], 200);
    }
}