<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{

    public function getByProvince($province_id)
    {
        $cities = City::where('province_id', $province_id)->get();

        return response()->json([
            'status' => true,
            'message' => 'Cities retrieved successfully for this province.',
            'data' => $cities
        ]);
    }
}
