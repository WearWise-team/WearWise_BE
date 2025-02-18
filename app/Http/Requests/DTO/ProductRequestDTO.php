<?php

namespace App\Http\Requests\DTO;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequestDTO extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Thay đổi giá trị này thành true nếu bạn muốn kiểm tra quyền truy cập của người dùng
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|url|max:2048', 
            'quantity' => 'required|integer|min:0',
            'supplier_id' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'name' => 'product name',
            'price' => 'product price',
            'description' => 'product description',
            'image' => 'product image',
            'quantity' => 'product quantity',
            'supplier_id' => 'product supplier',
        ];
    }
}
