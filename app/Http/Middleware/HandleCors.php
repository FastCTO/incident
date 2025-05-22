<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HandleSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() && $request->isMethod('POST')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Session expired. Please log in again.'], 419);
            }
            return redirect()->route('login')->with('error', 'Session expired. Please log in again.');
        }

        return $next($request);
    }
}

