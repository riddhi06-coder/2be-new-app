<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Send unauthenticated users (e.g. expired session) to the admin login page.
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        // Send already-authenticated users away from guest-only pages to the dashboard.
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
        // Per-route permission gate: ->middleware('permission:roles.view')
        $middleware->alias([
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
