<?php

namespace App\Http\Controllers\Api;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    
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
