<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Sesi Anda telah berakhir. Silakan masuk terlebih dahulu untuk mengakses portal admin.',
            ]);
        }

        $user = Auth::user();

        if (!$user->isAdmin()) {
            Auth::logout();
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Akses ditolak: Akun Anda tidak memiliki hak akses administratif.',
            ]);
        }

        return $next($request);
    }
}
