<?php

namespace App\Http\Controllers;
use App\Firebase\FirebaseService;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NotificationController;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    /*
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
*/
public function checkAvailability($apartment_id, $start_date, $end_date, $excludeBookingId = null)
{
    return !Booking::where('apartment_id', $apartment_id)
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'rejected') // استثناء المرفوض أيضاً
        ->when($excludeBookingId, function($query) use ($excludeBookingId) {
            return $query->where('id', '!=', $excludeBookingId); // استثناء الحجز الحالي
        })
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

/*
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
*/
public function update(Request $request, $id)
{
    $booking = Booking::where('id', $id)
                      ->where('user_id', Auth::id())
                      ->firstOrFail();

    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    // نمرر الـ ID هنا لكي لا يصطدم الحجز الجديد بالقديم في قاعدة البيانات
    $available = $this->checkAvailability(
        $booking->apartment_id,
        $request->start_date,
        $request->end_date,
        $id 
    );

    if (!$available) {
        return response()->json([
            'message' => 'This apartment is not available for the selected dates.'
        ], 422);
    }

    $booking->update([
        'requested_start_date' => $request->start_date,
        'requested_end_date' => $request->end_date,
        'status' => 'modified_pending', 
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Modification request sent to owner',
        'booking' => $booking
    ]);
}
public function destroy($id)
{//يمنع أي مستأجر من التلاعب بحجوزات الآخرين noura
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

public function ownerRequests()
{
    $ownerId = Auth::id();

    $bookings = Booking::whereHas('apartment', function ($query) use ($ownerId) {
        $query->where('owner_id', $ownerId);
    })
    ->whereIn('status', ['pending', 'modified_pending'])
    ->with([
    'tenant:id,first_name,last_name,phone', 
    'apartment:id,status,title'
])

    ->get();

    return response()->json($bookings);
}
// cases:
// - booking not found -> 404
// - not owner -> 403
// - pending -> confirm
// - modified_pending -> apply changes

public function approve($id)
{
    $booking = Booking::with('apartment')->find($id);

    if (!$booking) {
        return response()->json([
            'message' => 'Booking not found'
        ], 404);
    }

    if ($booking->apartment->owner_id !== Auth::id()) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }

    if ($booking->status === 'pending') {
        $booking->status = 'confirmed';
    }

    if ($booking->status === 'modified_pending') {
        $booking->start_date = $booking->requested_start_date;
        $booking->end_date = $booking->requested_end_date;
        $booking->requested_start_date = null;
        $booking->requested_end_date = null;
        $booking->status = 'confirmed';
    }

    $booking->save();
   
NotificationController::sendPushNotification(
            $booking->user_id, // Tenant ID
            'Booking Confirmed! ✅',
            'Your booking request for "' . $booking->apartment->title . '" has been approved.'
        );
    return response()->json([
        'message' => 'Booking approved successfully'
    ]);
}


public function reject($id)
{
    
    $booking = Booking::with('apartment')->find($id);

    if (!$booking) {
        return response()->json([
            'message' => 'Booking not found'
        ], 404);
    }

 
    if ($booking->apartment->owner_id !== Auth::id()) {
        return response()->json([
            'message' => 'Unauthorized'
        ], 403);
    }


    if (!in_array($booking->status, ['pending', 'modified_pending'])) {
        return response()->json([
            'message' => 'Booking cannot be rejected in its current state'
        ], 400);
    }

    $booking->status = 'rejected';
    $booking->requested_start_date = null;
    $booking->requested_end_date = null;
    $booking->save();
    NotificationController::sendPushNotification(
            $booking->user_id, 
            'Booking Rejected ❌',
            'Sorry, your booking request for "' . $booking->apartment->title . '" was not accepted.'
        );
    return response()->json([
        'message' => 'Booking rejected successfully'
    ]);
}

}