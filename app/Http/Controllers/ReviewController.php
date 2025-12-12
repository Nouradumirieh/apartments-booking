<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Container\Attributes\Auth;
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
    /* 
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

    // 3. إعادة التقييم كـ JSON
    return response()->json($review, 201);*/
}


    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        //
    }
}
