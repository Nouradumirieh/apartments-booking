<?php

namespace App\Http\Controllers;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
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
     public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'first_name' => 'nullable|string|max:50',
            'last_name'  => 'nullable|string|max:50',
            'dob'        => 'nullable|date',
        ]);

        $user->update($request->only([
            'first_name',
            'last_name',
            'dob'
        ]));

        return response()->json([
            'message' => 'Profile updated',
            'user' => $user
        ]);
    }

    
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:2048'
        ]);

        $path = $request->file('avatar')->store('avatars', 'public');

        $user = $request->user();
        $user->avatar = $path;
        $user->save();

        return response()->json([
            'message' => 'Avatar uploaded',
            'avatar_url' => asset('storage/' . $path)
        ]);
    }

    
    public function uploadID(Request $request)
    {
        $request->validate([
            'id_image' => 'required|image|max:2048'
        ]);

        $path = $request->file('id_image')->store('ids', 'public');

        $user = $request->user();
        $user->id_image = $path;
        $user->save();

        return response()->json([
            'message' => 'ID uploaded',
            'id_url' => asset('storage/' . $path)
        ]);
    }
}
