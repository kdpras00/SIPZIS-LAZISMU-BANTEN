<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockAdminFromPublic
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            Auth::logout();
            return redirect()->route('login')
                ->with('warning', 'Admin tidak boleh mengakses halaman umum.');
        }

        return $next($request);
    }
}
