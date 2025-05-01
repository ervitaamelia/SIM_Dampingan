<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        // Jika tidak ada roles yang ditentukan, izinkan akses
        if (empty($roles)) {
            return $next($request);
        }

        // Periksa apakah user memiliki salah satu dari roles yang diizinkan
        foreach ($roles as $role) {
            if (Auth::user()->role === $role) {
                return $next($request);
            }
        }

        return redirect('/'); // Atau abort(403) jika ingin forbidden
    }
}