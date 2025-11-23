<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register tenant middleware alias
        $middleware->alias([
            'tenant' => \App\Http\Middleware\InitializeTenancyByDomain::class,
        ]);

        // Apply tenant middleware to API routes
        $middleware->api(append: [
            \App\Http\Middleware\InitializeTenancyByDomain::class,
        ]);

        // Optionally apply to web routes too
        // $middleware->web(append: [
        //     \App\Http\Middleware\InitializeTenancyByDomain::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
