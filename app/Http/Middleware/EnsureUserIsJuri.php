<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsJuri
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Allow BOTH Admin and Juri
        if (!$user || (!$user->isJuri() && !$user->isAdmin())) {
            abort(403, 'Akses hanya untuk Juri atau Admin.');
        }

        return $next($request);
    }
}
