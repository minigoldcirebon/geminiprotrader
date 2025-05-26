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
    )    ->withMiddleware(function (Middleware $middleware) {        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'api.key' => \App\Http\Middleware\ApiKeyAuth::class,
            'api.rate' => \App\Http\Middleware\ApiRateLimit::class,
            'webhook.rate' => \App\Http\Middleware\WebhookRateLimit::class,
        ]);// Custom throttle configurations (temporarily disabled to avoid Redis dependency)
        // $middleware->throttleApi('webhook', 100, 1); // 100 requests per minute for webhooks

        // Disable CSRF for testing environment
        if (env('APP_ENV') === 'testing') {
            $middleware->validateCsrfTokens(except: [
                '*'
            ]);
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
