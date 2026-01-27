<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PesertaMagang
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cek apakah user role adalah peserta_magang
        if (!$user->hasRole('peserta_magang')) {
            abort(403, 'Anda tidak memiliki akses ke fitur ini.');
        }

        // Cek apakah user sudah di-approve
        if (!$user->is_approved) {
            abort(403, 'Akun Anda masih menunggu approval dari admin.');
        }

        return $next($request);
    }
}
