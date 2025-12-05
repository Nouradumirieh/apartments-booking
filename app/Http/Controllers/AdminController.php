<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
  
    public function pendingUsers()
    {
        $users = User::where('status', 'pending')->get();

        return response()->json([
            'pending_users' => $users
        ]);
    }

   
    public function approveUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'approved';
        $user->save();

        return response()->json([
            'message' => 'User approved successfully',
            'user' => $user
        ]);
    }

   
    public function rejectUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'rejected';
        $user->save();

        return response()->json([
            'message' => 'User rejected successfully',
            'user' => $user
        ]);
    }
}
