<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApartmentController extends Controller
{
   public function index(Request $request)
{
    $query = Apartment::query()
        ->where('admin_status', 'approved') 

        
      //  ->orderByRaw("CASE WHEN booking_status = 'available' THEN 0 ELSE 1 END")
        ->orderBy('created_at', 'desc');

    
    $filters = [
        'province_id' => 'province_id',
        'city_id' => 'city_id',
        'max_price' => 'price',
        'min_rooms' => 'number_of_rooms',
        'has_elevator' => 'has_elevator'
    ];

    foreach ($filters as $key => $column) {
        if ($request->filled($key)) {
            switch ($key) {
                case 'max_price':
                    $query->where($column, '<=', $request->$key);
                    break;
                case 'min_rooms':
                    $query->where($column, '>=', $request->$key);
                    break;
                case 'has_elevator':
                    $query->where($column, $request->$key ? 1 : 0);
                    break;
                default:
                    $query->where($column, $request->$key);
                    break;
            }
        }
    }

    
  $query->with([
    'owner:id,first_name,last_name',
    'province:id,name',
    'city:id,name'
]);


    
    $apartments = $query->paginate(15);

    
    $apartments->getCollection()->transform(function ($apartment) {
        $apartment->is_booked = $apartment->booking_status === 'booked';
        return $apartment;
    });

    return response()->json([
        'status' => true,
        'message' => 'Apartments list retrieved successfully.',
        'data' => $apartments
    ]);
}

    public function show($id)
    {

        $apartment = Apartment::with(['owner', 'province', 'city'])
        ->where('admin_status', 'approved')
            ->find($id);

        if (!$apartment) {
            return response()->json([
                'status' => false,
                'message' => 'Apartment not found.'
            ], 404);
        }
 $apartment->is_booked = $apartment->booking_status === 'booked';
        return response()->json([
            'status' => true,
            'message' => 'Apartment details retrieved successfully.',
            'data' => $apartment
        ]);
    }

    public function store(StoreApartmentRequest $request)
    {
        $user = $request->user();
      /*$image_paths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('apartments_images', 'public');
                $image_paths[] = Storage::url($path);
            
        }*/
 $image_paths = [];

if ($request->hasFile('images')) {
    foreach ($request->file('images') as $image) {
        $path = $image->store('apartments', 'public');
        $image_paths[] = Storage::url($path);
        // مثال: /storage/apartments/abc123.jpg
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
               'admin_status' => 'pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Apartment added successfully and pending admin approval.',
            'apartment' => $apartment
        ], 201);
    }

    public function update(UpdateApartmentRequest $request, $id)
{
    $apartment = Apartment::find($id);
     
    
    if (!$apartment) {
        return response()->json(['status' => false, 'message' => 'Apartment not found'], 404);
    }

    /*
    if (Auth::id() !== $apartment->owner_id) {
        return response()->json(['status' => false, 'message' => 'Unauthorized access.'], 403);
    }*/


    

    
    $data = $request->except('images'); 

    if ($request->hasFile('images')) {
        
      if ($apartment->images) {
    foreach ($apartment->images as $imageUrl) {
        $path = str_replace('/storage/', '', $imageUrl);
        Storage::disk('public')->delete($path);
    }
}


        
       $image_paths = [];

foreach ($request->file('images') as $image) {
    $path = $image->store('apartments', 'public');
    $image_paths[] = Storage::url($path);
}

$data['images'] = $image_paths;

    }

    
    $data['admin_status'] = 'pending';

    $apartment->update($data);

    return response()->json([
        'status' => true, 
        'message' => 'Apartment updated successfully and under review.', 
        'data' => $apartment
    ], 200);
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
