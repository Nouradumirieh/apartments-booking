<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Apartment::query();


        $query->where('status', 'available');


        if ($request->has('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        if ($request->has('city_id')) {
            $query->where('city_id', $request->city_id);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }


        if ($request->has('min_rooms')) {
            $query->where('number_of_rooms', '>=', $request->min_rooms);
        }

        if ($request->has('has_elevator')) {
            $query->where('has_elevator', $request->has_elevator);
        }


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

    public function store(Request $request)
    {
        $user = Auth::user();


        if (!$user || $user->role !== 'owner') {
            return response()->json([
                'status' => false,
                'message' => 'Not authorized. Only owners can add apartments.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'province_id' => 'required|exists:provinces,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'address_details' => 'required|string',
            'number_of_rooms' => 'required|integer|min:1',
            'number_of_bathrooms' => 'required|integer|min:1',
            'area' => 'required|numeric|min:10',
            'has_elevator' => 'required|boolean',
            'has_balcony' => 'required|boolean',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Data verification failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $image_paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('apartments_images', 'public');
                $image_paths[] = Storage::url($path);
            }
        }
        $apartment = Apartment::create([
            'owner_id' => $user->id,
            'city_id' => $request->city_id,
            'province_id' => $request->province_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'address_details' => $request->address_details,
            'number_of_rooms' => $request->number_of_rooms,
            'number_of_bathrooms' => $request->number_of_bathrooms,
            'area' => $request->area,
            'has_elevator' => $request->has_elevator,
            'has_balcony' => $request->has_balcony,
            'images' => $image_paths,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Apartment added successfully and pending admin approval.',
            'apartment' => $apartment
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'The apartment does not exist'], 404);
        }


        if (Auth::id() !== $apartment->owner_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $apartment->update($request->all());

        return response()->json(['message' => 'Edited successfully', 'data' => $apartment], 200);
    }

    public function destroy($id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'The apartment does not exist'], 404);
        }

        if (Auth::id() !== $apartment->owner_id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $apartment->delete();
        return response()->json(['message' => 'Deleted successfully'], 200);
    }
}
