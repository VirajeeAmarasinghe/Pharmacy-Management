<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\OwnerMiddleware;
use App\Http\Middleware\ManagerMiddleware;
use App\Http\Middleware\CashierMiddleware;
use App\Http\Middleware\ManagerOrOwnerMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'web' => \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class,
            'owner' => OwnerMiddleware::class,
            "manager" => ManagerMiddleware::class,
            "cashier" => CashierMiddleware::class,
            "managerOrOwner" => ManagerOrOwnerMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
