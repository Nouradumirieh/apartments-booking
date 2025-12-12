<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function checkAvailability($apartment_id, $start_date, $end_date)
{
    return !Booking::where('apartment_id', $apartment_id)
        ->where('status', '!=', 'cancelled') 
        ->where(function($query) use ($start_date, $end_date) {
            $query->whereBetween('start_date', [$start_date, $end_date])
                  ->orWhereBetween('end_date', [$start_date, $end_date])
                  ->orWhere(function($q) use ($start_date, $end_date) {
                      $q->where('start_date', '<=', $start_date)
                        ->where('end_date', '>=', $end_date);
                  });
        })
        ->exists();
}

public function store(Request $request)
{
    $request->validate([
        'apartment_id' => 'required|exists:apartments,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $available = $this->checkAvailability(
        $request->apartment_id,
        $request->start_date,
        $request->end_date
    );

    if (!$available) {
        return response()->json(['message' => 'This apartment is not available for the selected dates.'], 422);
    }

    $booking = Booking::create([
      'user_id' => Auth::id(),
      'apartment_id' => $request->apartment_id,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => 'pending',
    ]);

    return response()->json($booking, 201);
}


public function update(Request $request, $id)
{
    $booking = Booking::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    
    $available = $this->checkAvailability(
        $booking->apartment_id,
        $request->start_date,
        $request->end_date
    );

    if (!$available) {
        return response()->json([
            'message' => 'This apartment is not available for the selected dates.'
        ], 422);
    }

    $booking->update([
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'status' => 'modified_pending', 
    ]);

    return response()->json($booking);
}

public function destroy($id)
{
    $booking = Booking::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

    $booking->update([
        'status' => 'cancelled'
    ]);

    return response()->json([
        'message' => 'Booking cancelled successfully.',
        'booking' => $booking
    ]);
}

public function myBookings()
{
    
    $bookings = Booking::where('user_id', Auth::id())
                        ->orderBy('start_date', 'desc') 
                        ->get();//Descending ترتيب تنازلي

    return response()->json($bookings);
}



}
