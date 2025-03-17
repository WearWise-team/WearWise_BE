<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class ReviewRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [ 
            'order_item_id' => 'required|exists:order_items,id', 
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string|min:10' 
        ];
    }
}
