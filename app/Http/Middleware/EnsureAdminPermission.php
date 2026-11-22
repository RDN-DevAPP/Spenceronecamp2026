<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses panel admin ditolak.');
        }

        if (!$user->hasPermission($feature)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk fitur ini.',
                ], 403);
            }

            // Jika user berhak mengakses dashboard dan fitur yang ditolak bukan dashboard, redirect ke dashboard
            if ($user->hasPermission('dashboard') && $feature !== 'dashboard') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk fitur tersebut. Hubungi Admin Utama.');
            }

            // Jika user tidak berhak dashboard, cari route pertama yang diizinkan untuknya
            $firstRoute = $user->getFirstPermittedRoute();
            if ($firstRoute && $firstRoute !== url()->current()) {
                return redirect($firstRoute)->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk fitur tersebut.');
            }

            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses modul admin ini.');
        }

        return $next($request);
    }
}
