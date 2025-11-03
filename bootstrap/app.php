<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {

                $response = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
                $statusCode = 500;
                if ($e instanceof ValidationException) {
                    $statusCode = 422;
                    $response['errors'] = $e->errors();
                } elseif (method_exists($e, 'getStatusCode')) {
                    $statusCode = $e->getStatusCode();
                }
                if ($statusCode === 500 && app()->environment('production')) {
                    $response['message'] = 'Erro interno do servidor';
                }

                return response()->json($response, $statusCode);
            }
        });
    })->create();
