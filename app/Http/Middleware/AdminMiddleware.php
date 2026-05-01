<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Harus login dulu
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Harus role admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }

        return $next($request);
    }
}
