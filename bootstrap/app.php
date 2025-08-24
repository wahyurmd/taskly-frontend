<?php

use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckGuest;
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
        $middleware->alias([
            'check.auth' => CheckAuth::class,
            'check.guest' => CheckGuest::class,
        ]);
        $middleware->web(append: [
            \RealRashid\SweetAlert\ToSweetAlert::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
