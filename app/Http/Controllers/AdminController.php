<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminController extends Controller
{
  
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
    
    $users = User::select('id', 'phone', 'role', 'status','id_image', 'created_at')
                 ->get();

    return response()->json([
        'users' => $users
    ]);
}



}
