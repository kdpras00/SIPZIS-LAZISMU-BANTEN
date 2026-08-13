<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->user();

        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
        }

        // Check if user has any of the required roles
        if (!empty($roles)) {
            // Using Spatie's hasAnyRole
            if (!$user->hasAnyRole($roles)) {
                $userRoles = $user->roles->pluck('name')->join(', ');
                \Log::warning('Role mismatch', [
                    'user_id' => $user->id,
                    'user_roles' => $userRoles,
                    'required_roles' => $roles,
                    'path' => $request->path()
                ]);
                abort(403, 'Anda tidak memiliki akses ke halaman ini. Role Anda: ' . ($userRoles ?: 'Tidak ada'));
            }
        }

        return $next($request);
    }
}
