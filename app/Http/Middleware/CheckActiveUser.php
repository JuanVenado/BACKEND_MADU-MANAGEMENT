<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveUser
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'message' => 'No autenticado.',
                'error' => 'unauthenticated',
            ], Response::HTTP_UNAUTHORIZED);
        }

        if (data_get($user, 'activo') === false) {
            return response()->json([
                'message' => 'La cuenta está desactivada.',
                'error' => 'inactive_account',
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
