<?php

declare(strict_types=1);

use Hybridly\Exceptions\HandleHybridExceptions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('web', [\App\Http\Middleware\HandleHybridRequests::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //        HandleHybridExceptions::register()
        //            ->renderUsing(fn (Response $response) => view('error', [
        //                'status' => $response->getStatusCode(),
        //            ]));
    })->create();
