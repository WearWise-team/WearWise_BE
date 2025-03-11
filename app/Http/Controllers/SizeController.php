<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Contracts\ISizeService;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    protected $sizeService;

    public function __construct(ISizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }

    public function index()
    {
        $sizes = $this->sizeService->getAllSizes();
        return response()->json($sizes);
    }
    
}