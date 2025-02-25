<?php

namespace App\Http\Controllers;

use App\Services\Contracts\IColorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ColorController extends Controller
{
    protected $colorService;

    public function __construct(IColorService $colorService)
    {
        $this->colorService = $colorService;
    }

    public function index(): JsonResponse
    {
        $colors = $this->colorService->getAllColors();
        return response()->json(['data' => $colors], 200);
    }

    public function show(int $id): JsonResponse
    {
        $color = $this->colorService->getColorById($id);
        if (!$color) {
            return response()->json(['message' => 'Color not found'], 404);
        }
        return response()->json(['data' => $color], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|unique:colors,name',
            'code' => 'required|string|size:7|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $color = $this->colorService->createColor($validatedData);
        return response()->json(['message' => 'Color created successfully', 'data' => $color], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|unique:colors,name,' . $id,
            'code' => 'sometimes|required|string|size:7|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        $updated = $this->colorService->updateColor($validatedData, $id);
        if (!$updated) {
            return response()->json(['message' => 'Color not found or update failed'], 404);
        }

        return response()->json(['message' => 'Color updated successfully'], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->colorService->deleteColor($id);
        if (!$deleted) {
            return response()->json(['message' => 'Color not found or delete failed'], 404);
        }

        return response()->json(['message' => 'Color deleted successfully'], 200);
    }
}
