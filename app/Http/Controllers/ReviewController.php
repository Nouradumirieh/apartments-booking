<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ReviewController extends Controller
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

        $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        
        $hasBooked = Booking::where('user_id', Auth::id())
                            ->where('apartment_id', $request->apartment_id)
                            ->where('status', 'confirmed')
                            ->exists();

        if (!$hasBooked) {
            return response()->json(['message' => 'You can only rate apartments you have booked.'], 403);
        }

        
        $review = Review::create([
            'user_id' => Auth::id(),
            'apartment_id' => $request->apartment_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Review added successfully.',
            'review' => $review
        ], 201);
    }



    /**
     * Display the specified resource.
     */
   public function show($apartmentId)
{
    $reviews = Review::where('apartment_id', $apartmentId)
        ->with('user:id,first_name,last_name')
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Apartment reviews retrieved successfully',
        'data' => $reviews
    ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Review $review)
{
    
    if ($review->user_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $request->validate([
        'rating' => 'integer|min:1|max:5',
        'comment' => 'nullable|string|max:1000',
    ]);

    $review->update($request->only(['rating', 'comment']));

    return response()->json([
        'message' => 'Review updated successfully.',
        'review' => $review
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
  public function destroy(Review $review)
{
    
    if ($review->user_id !== Auth::id()) {
        return response()->json(['message' => 'Unauthorized'], 403);
    }

    $review->delete();

    return response()->json([
        'message' => 'Review deleted successfully.'
    ]);
}

    public function apartmentReviews($apartmentId)
{
    
    $reviews = Review::where('apartment_id', $apartmentId)
                     ->with('user:id,first_name,last_name') 
                     ->orderBy('created_at', 'desc') 
                     ->get();

    return response()->json([
        'status' => true,
        'message' => 'Apartment reviews retrieved successfully',
        'data' => $reviews
    ]);
}

}
