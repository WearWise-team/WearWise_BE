<?php

namespace App\Http\Controllers;

use App\Services\Contracts\ISupplierService;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    protected $supplierService;

    public function __construct(ISupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        $suppliers = $this->supplierService->getAllSuppliers();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $supplier = $this->supplierService->createSupplier($validated);
        return response()->json($supplier, 201);
    }

    public function show($id)
    {
        $supplier = $this->supplierService->getSupplierById((int) $id);
        return response()->json($supplier);
    }

    public function update($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string',
        ]);

        $supplier = $this->supplierService->updateSupplier($validated, (int) $id);
        return response()->json($supplier);
    }

    public function destroy($id)
    {
        $this->supplierService->deleteSupplier((int) $id);
        return response()->json(null, 204);
    }

    public function getSupplierByUserID($user_id)
    {
        $supplier = $this->supplierService->getSupplierByUserID($user_id);

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        return response()->json($supplier);
    }
}