<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreApartmentRequest extends FormRequest
{
    
    public function authorize(): bool
    {

        return Auth::check() && Auth::user()->role === 'owner';
    }

  
    public function rules(): array
    {
        return [
            'city_id'             => 'required|exists:cities,id',
            'province_id'         => 'required|exists:provinces,id',
            'title'               => 'required|string|max:255',
            'description'         => 'required|string',
            'price'               => 'required|numeric|min:1',
            'address_details'     => 'required|string',
            'number_of_rooms'     => 'required|integer|min:1',
            'number_of_bathrooms' => 'required|integer|min:1',
            'area'                => 'required|numeric|min:10',
            'has_elevator'        => 'required|boolean',
            'has_balcony'         => 'required|boolean',
            'images'              => 'required|array|min:1|max:5',
            'images.*'            => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'city_id.required' => 'Please select a city.',
            'province_id.required' => 'Please select a province.',
            'title.required' => 'Title is required.',
            'images.*.image' => 'Each file must be a valid image (jpeg, png, jpg, gif).',
            'images.*.max' => 'Each image must not exceed 2MB.',
        ];
    }
}
