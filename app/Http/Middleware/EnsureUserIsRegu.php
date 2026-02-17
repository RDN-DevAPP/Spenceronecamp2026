<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsRegu
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isRegu()) {
            abort(403, 'Akses hanya untuk Peserta (Regu).');
        }

        return $next($request);
    }
}
