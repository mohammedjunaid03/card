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
        // Register middleware aliases
        $middleware->alias([
            'user' => \App\Http\Middleware\UserMiddleware::class,
            'hospital' => \App\Http\Middleware\HospitalMiddleware::class,
            'staff' => \App\Http\Middleware\StaffMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'audit_log' => \App\Http\Middleware\AuditLogMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
