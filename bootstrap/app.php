<?php

require_once __DIR__ . '/reverb_autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'api/dana/webhook',
            'api/pakasir/webhook',
        ]);
        $middleware->web(append: [
            // \App\Http\Middleware\HandleInertiaRequests::class, // Uncomment setelah install: composer require inertiajs/inertia-laravel
        ]);
        $middleware->alias([
            'juri' => \App\Http\Middleware\EnsureUserIsJuri::class,
            'regu' => \App\Http\Middleware\EnsureUserIsRegu::class,
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'admin.permission' => \App\Http\Middleware\EnsureAdminPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
