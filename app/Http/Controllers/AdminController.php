<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
class AdminController extends Controller
{
   public function loginadmin(Request $request) {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('phone', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['phone' => 'Not an admin account']);
            }
            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['phone' => 'Invalid credentials']);
    }
   

public function logoutadmin(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
}

 public function pendingUsers()
{
    $users = User::where('status', 'pending')
                 ->select('id','phone', 'role', 'id_image', 'created_at')
                 ->get();

    return response()->json([
        'pending_users' => $users
    ]);
}


   
  public function approveUser($id)
{
    try {
        $user = User::findOrFail($id);

        if ($user->status === 'approved') {
            return response()->json(['message' => 'User is already approved'], 400);
        }

        $user->status = 'approved';
        $user->save();

        return response()->json([
            'message' => 'User approved successfully. They can now log in.',
            'user' => $user
        ]);
    } catch (ModelNotFoundException $e) {
       
        return response()->json([
            'error' => 'Resource Not Found',
            'message' => "The user with ID {$id} does not exist."
        ], 404);
    }
}
   
    public function rejectUser($id)
    {
        $user = User::find($id); 

    if (is_null($user)) { 
        return response()->json([
            'error' => 'Resource Not Found',
            'message' => "The user with ID {$id} was not found in the database."
        ], 404); 
    }

    if ($user->status === 'rejected') {
        return response()->json(['message' => 'User is already rejected'], 400);
    }
    
        $user->status = 'rejected';
        $user->save();

        return response()->json([
            'message' => 'User rejected successfully',
            'user' => $user
        ]);
    }
public function allUsers()
{
    // Check if user is logged in to avoid errors
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $users = User::where('id', '!=', Auth::id()) // Use Auth::id() instead of auth()->id()
                 ->select('id', 'phone', 'role', 'status', 'id_image', 'created_at')
                 ->latest()
                 ->get();

    return response()->json([
        'status' => 'success',
        'users' => $users
    ]);
}

public function pendingApartments()
{
    $apartments = Apartment::where('admin_status', 'pending')
        ->with(['owner', 'province', 'city'])
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'message' => 'Pending apartments retrieved successfully.',
        'data' => $apartments
    ]);
}
public function approveApartment($id)
{
    $apartment = Apartment::find($id);

    if (!$apartment) {
        return response()->json(['status' => false, 'message' => 'Apartment not found'], 404);
    }

    $apartment->admin_status = 'approved';
    $apartment->save();

    return response()->json([
        'status' => true,
        'message' => 'Apartment approved successfully.',
        'data' => $apartment
    ]);
}
public function rejectApartment($id)
{
    $apartment = Apartment::find($id);

    if (!$apartment) {
        return response()->json(['status' => false, 'message' => 'Apartment not found'], 404);
    }

    $apartment->admin_status = 'rejected';
    $apartment->save();

    return response()->json([
        'status' => true,
        'message' => 'Apartment rejected successfully.',
        'data' => $apartment
    ]);
}

public function deleteUser($id)
{
    try {
        // Find the user or fail with 404
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return response()->json([
                'status' => 'error', 
                'message' => 'You cannot delete your own account'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Something went wrong while deleting'
        ], 500);
    }
}

}
