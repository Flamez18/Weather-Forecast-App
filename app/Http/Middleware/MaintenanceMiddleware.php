<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Setting::get('maintenance_mode') === '1') {

            // Admin yang sudah login — lewat bebas
            if (Auth::check() && Auth::user()->role === 'admin') {
                return $next($request);
            }

            // Halaman auth (login, register, forgot password, logout)
            // SELALU boleh diakses oleh siapapun
            if ($request->is('login') ||
                $request->is('register') ||
                $request->is('forgot-password') ||
                $request->is('logout') ||
                $request->routeIs('login') ||
                $request->routeIs('register') ||
                $request->routeIs('password.request') ||
                $request->routeIs('logout')) {
                return $next($request);
            }

            // Semua halaman lain → maintenance
            return response(view('maintenance'), 503);
        }

        return $next($request);
    }
}
