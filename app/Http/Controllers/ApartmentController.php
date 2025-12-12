<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\User;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{
   public function index(Request $request)
    {

        $query = Apartment::query();


        $query->with(['owner', 'province', 'city']);


        $query->latest();


        $apartments = $query->paginate(15);

        return response()->json([
            'status' => true,
            'message' => 'Apartments list retrieved successfully.',
            'data' => $apartments
        ]);
    }


    public function show($id)
    {

        $apartment = Apartment::with(['owner', 'province', 'city'])
            ->where('id', $id)
            ->first();


        if (!$apartment) {
            return response()->json([
                'status' => false,
                'message' => 'Apartment not found.'
            ], 404);
        }



        return response()->json([
            'status' => true,
            'message' => 'Apartment details retrieved successfully.',
            'data' => $apartment
        ]);
    }

    // منضيق لقدام  Store, Update, Delete.

}
