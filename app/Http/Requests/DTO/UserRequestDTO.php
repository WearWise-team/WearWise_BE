<?php

namespace App\Http\Requests\DTO;

use Illuminate\Foundation\Http\FormRequest;

class UserRequestDTO extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $this->user,
            'password'   => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'string',
                'min:8', 
                'regex:/[A-Z]/', 
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[\W_]/',
            ],
            'phone'      => 'nullable|string|regex:/^[0-9\-\+]{9,15}$/',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address'    => 'nullable|string|max:500',
            'role'       => 'required|in:admin,user,supplier',
            'weight'     => 'nullable|numeric|min:1|max:300',
            'height'     => 'nullable|numeric|min:50|max:250',
            'shirt_size' => 'nullable|string|in:S,M,L,XL,XXL',
            'pant_size'  => 'nullable|string|in:28,30,32,34,36,38,40',
            'gender'     => 'required|in:male,female,other',
        ];
    }
    
    public function messages()
    {
        return [
            'name.required'       => 'The name field is required.',
            'name.string'         => 'The name must be a string.',
            'name.max'           => 'The name must not exceed 255 characters.',

            'email.required'      => 'The email field is required.',
            'email.email'         => 'The email must be a valid email address.',
            'email.unique'        => 'The email has already been taken.',

            'password.required'   => 'The password field is required.',
            'password.string'     => 'The password must be a string.',
            'password.min'        => 'The password must be at least 8 characters long.',
            'password.regex'      => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',

            'phone.string'        => 'The phone number must be a string.',
            'phone.regex'         => 'The phone number must be between 9 and 15 digits and may include "+" or "-".',

            'avatar.image'        => 'The avatar must be an image.',
            'avatar.mimes'        => 'The avatar must be a file of type: jpeg, png, jpg, gif.',
            'avatar.max'          => 'The avatar size must not exceed 2MB.',

            'address.string'      => 'The address must be a string.',
            'address.max'         => 'The address must not exceed 500 characters.',

            'role.required'       => 'The role field is required.',
            'role.in'             => 'The role must be either "admin" or "user".',

            'weight.numeric'      => 'The weight must be a numeric value.',
            'weight.min'          => 'The weight must be at least 1 kg.',
            'weight.max'          => 'The weight must not exceed 300 kg.',

            'height.numeric'      => 'The height must be a numeric value.',
            'height.min'          => 'The height must be at least 50 cm.',
            'height.max'          => 'The height must not exceed 250 cm.',

            'shirt_size.string'   => 'The shirt size must be a string.',
            'shirt_size.in'       => 'The shirt size must be one of the following: XS, S, M, L, XL, XXL.',

            'pant_size.string'    => 'The pant size must be a string.',
            'pant_size.in'        => 'The pant size must be one of the following: 28, 30, 32, 34, 36, 38, 40.',

            'gender.required'     => 'The gender field is required.',
            'gender.in'           => 'The gender must be one of the following: male, female, other.',
        ];
    }

}