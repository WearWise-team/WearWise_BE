<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contracts\IVirtualTryOnService;
use Illuminate\Http\Request;

class VirtualTryOnController extends Controller
{
    protected $virtualTryOnService;

    public function __construct(IVirtualTryOnService $virtualTryOnService)
    {
        $this->virtualTryOnService = $virtualTryOnService;
    }

    public function tryOnClothes(Request $request)
    {
        try {
            if (!$request->hasFile('person_image') || !$request->hasFile('cloth_image')) {
                return response()->json(['error' => 'File upload failed - No file detected'], 400);
            }

            $personImage = $request->file('person_image');
            $clothImage = $request->file('cloth_image');

            // Gọi service thử đồ
            $result = $this->virtualTryOnService->tryOnClothes($personImage, $clothImage);

            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}