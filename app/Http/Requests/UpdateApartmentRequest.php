<?php

namespace App\Http\Requests;

use App\Models\Apartment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateApartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
$apartment = Apartment::find($this->route('id'));
return $apartment && Auth::id() === $apartment->owner_id;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
           return [
        'city_id'             => 'sometimes|exists:cities,id',
        'province_id'         => 'sometimes|exists:provinces,id',
        'title'               => 'sometimes|string|max:255',
        'description'         => 'sometimes|string',
        'price'               => 'sometimes|numeric|min:1',
        'address_details'     => 'sometimes|string',
        'number_of_rooms'     => 'sometimes|integer|min:1',
        'number_of_bathrooms' => 'sometimes|integer|min:1',
        'area'                => 'sometimes|numeric|min:10',
        'has_elevator'        => 'sometimes|boolean',
        'has_balcony'         => 'sometimes|boolean',
        'images'              => 'sometimes|array|min:1|max:5',
        'images.*'            => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ];
    }
}
