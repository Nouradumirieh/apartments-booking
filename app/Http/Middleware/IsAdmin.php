<?php

namespace App\Http\Middleware;
/*
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class IsAdmin
{
      public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) return redirect('login');

        if (Auth::user()->role !== 'admin') abort(403,'Unauthorized action.');

        return $next($request);
    }
}
*/
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->route('login');
        }

        if (Auth::user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}