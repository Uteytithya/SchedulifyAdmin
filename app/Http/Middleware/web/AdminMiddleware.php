<?php

namespace App\Http\Middleware\web;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }
        return redirect()->route('admin.login')->withErrors(['email' => 'Access denied.']);
    }
}
