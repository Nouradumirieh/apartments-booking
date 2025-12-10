<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
 use App\Http\Requests\RegisterRequest;
class AuthController extends Controller
{
   
/*
public function register(RegisterRequest $request)
{
    
    $imageName = time().'_'.$request->id_image->getClientOriginalName();
    $request->id_image->move(public_path('id_images'), $imageName);

    
    $user = User::create([
        'phone' => $request->phone,
        'role'  => $request->role,
        'password' => Hash::make($request->password),
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'dob' => $request->dob,
        'id_image' => $imageName,
        'status' => 'pending',
    ]);

   
    $token = $user->createToken('authToken')->plainTextToken;

   
    return response()->json([
        'message' => 'Registration successful',
        'user' => $user,
        'id_image_url' => asset('id_images/'.$imageName),
        'access_token' => $token,
        'token_type' => 'Bearer',
    ], 201);
}

*/
  
    public function login(Request $request)
{
    $request->validate([
        'phone' => 'required|string',
        'password' => 'required|string',
        // 'role' => 'required|in:tenant,owner',
    ]);

    $user = User::where('phone', $request->phone)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'phone' => ['The provided credentials are incorrect.'],
        ]);
    }

    
    if ($user->status === 'pending') {
        return response()->json([
            'message' => 'Your account is not approved by admin yet.'
        ], 403);
    }

    if ($user->status === 'rejected') {
        return response()->json([
            'message' => 'Your account is rejected.'
        ], 403);
    }

 
    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'user' => [
            'id' => $user->id,
            'phone' => $user->phone,
            // 'role' => $user->role,
            'created_at' => $user->created_at,
        ],
        'access_token' => $token,
        'token_type' => 'Bearer'
    ]);
}


    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
