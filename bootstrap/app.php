<?php

use App\Http\Middleware\CheckActiveUser;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(
            fn (Request $request) => $request->is('api', 'api/*') ? null : '/login',
        );
        $middleware->alias([
            'active' => CheckActiveUser::class,
            'role' => CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api', 'api/*') || $request->expectsJson(),
        );

        $exceptions->respond(function (Response $response, Throwable $exception, Request $request): Response {
            if (! $request->is('api', 'api/*')) {
                return $response;
            }

            $status = $response->getStatusCode();
            $data = null;
            $message = match ($status) {
                400 => 'Solicitud inválida.',
                401 => 'Autenticación requerida.',
                403 => 'Acceso denegado.',
                404 => 'Recurso no encontrado.',
                405 => 'Método no permitido.',
                429 => 'Demasiadas solicitudes.',
                default => $status >= 500 ? 'Error interno del servidor.' : 'No se pudo procesar la solicitud.',
            };

            if ($exception instanceof ValidationException) {
                $status = 400;
                $data = ['errors' => $exception->errors()];
                $message = 'Los datos de la solicitud no son válidos.';
            }

            $headers = $response->headers->all();
            unset($headers['content-type'], $headers['content-length']);

            if ($exception instanceof AuthenticationException && $request->routeIs('api.user')) {
                return response()->json([
                    'message' => 'No autenticado.',
                    'error' => 'unauthenticated',
                ], $status, $headers);
            }

            return response()->json([
                'success' => false,
                'data' => $data,
                'message' => $message,
            ], $status, $headers);
        });
    })->create();
