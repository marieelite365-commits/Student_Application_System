<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withProviders([
        App\Providers\AppServiceProvider::class,
        App\Providers\FortifyServiceProvider::class,
    ])

    ->withMiddleware(function (Middleware $middleware) {

        // ── Fix: After login, redirect based on role ──────────────
        $middleware->redirectUsersTo(function (Request $request) {
            $user = auth()->user();
            if ($user && $user->isAdmin()) {
                return route('admin.dashboard');
            }
            return route('student.dashboard');
        });

        // ── Middleware aliases ─────────────────────────────────────
        $middleware->alias([
            'admin'            => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'student'          => \App\Http\Middleware\EnsureUserIsStudent::class,
            'profile.complete' => \App\Http\Middleware\EnsureProfileComplete::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();