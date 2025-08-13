<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (UnauthorizedException $e, $request) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        });

        $exceptions->renderable(function (AuthorizationException $e, $request) {
            return redirect()->route('guru.dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        });

        $exceptions->renderable(function (Throwable $e, $request) {
            if (!app()->environment('local')) {
                return redirect()->route('guru.dashboard')->with('error', 'Terjadi kesalahan pada aplikasi.');
            }
        });
    })->create();
