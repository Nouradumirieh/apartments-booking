<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
    {
        //
    }
  public function getByProvince($province_id)
    {
          $cities = City::where('province_id', $province_id)->get();

        if ($cities->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No cities found for this province.',
                'data' => []
            ], 200); 
        }

        return response()->json([
            'status' => true,
            'message' => 'Cities retrieved successfully for this province.',
            'data' => $cities
        ], 200);
    }
}
