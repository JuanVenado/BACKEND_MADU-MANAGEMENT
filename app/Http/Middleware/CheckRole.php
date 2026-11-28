<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'message' => 'No autenticado.',
                'error' => 'unauthenticated',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (! in_array(data_get($user, 'rol.nombre'), $roles, true)) {
            return response()->json([
                'message' => 'No tienes permiso para realizar esta acción.',
                'error' => 'forbidden',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
