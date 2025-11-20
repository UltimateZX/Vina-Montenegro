<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// IMPORTANTE: Usamos el nombre real de tu archivo
use App\Http\Middleware\CheckAdminRole; 

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Solución Error 419 (Proxy de Google)
        $middleware->trustProxies(at: '*');

        // Solución Error "Target class admin does not exist"
        $middleware->alias([
            'admin' => CheckAdminRole::class,
        ]);
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();