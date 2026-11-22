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

        // Only allow Juri role
        if (!$user || !$user->isJuri()) {
            abort(403, 'Akses hanya untuk Juri.');
        }

        return $next($request);
    }
}
