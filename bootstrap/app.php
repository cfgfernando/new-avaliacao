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
        $middleware->validateCsrfTokens(except: [
            'api/v1/integration/*',
        ]);

        // Alias do Middleware RBAC: uso nas rotas via ->middleware('role:Admin,Supervisor')
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureRole::class,
            'accounting_closure' => \App\Http\Middleware\CheckAccountingClosure::class,
            'integration_token' => \App\Http\Middleware\IntegrationTokenMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function ($schedule): void {
        $schedule->command('system:backup --disk=s3')->dailyAt('03:00');
    })->create();
