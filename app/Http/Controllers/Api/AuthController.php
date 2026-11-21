<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credenciales = $request->validated();
        $usuario = Usuario::where('nombre', $credenciales['nombre'])->first();

        if (!$usuario || !Hash::check($credenciales['password'], $usuario->password)) {
            throw ValidationException::withMessages([
                'nombre' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!$usuario->activo) {
            throw ValidationException::withMessages([
                'nombre' => ['El usuario se encuentra inactivo.'],
            ]);
        }

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => ['usuario' => $usuario, 'token' => $token],
            'message' => 'Inicio de sesion exitoso',
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'data' => null, 'message' => 'Sesion cerrada'], 200);
    }
}
