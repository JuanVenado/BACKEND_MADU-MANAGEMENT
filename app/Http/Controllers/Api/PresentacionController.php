<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SavePresentacionRequest;
use App\Models\Presentacion;
use Illuminate\Http\JsonResponse;

class PresentacionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Presentacion::all(),
            'message' => 'Listado de presentaciones',
        ]);
    }

    public function store(SavePresentacionRequest $request): JsonResponse
    {
        $presentacion = Presentacion::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $presentacion->refresh(),
            'message' => 'Registro de presentación creado',
        ], 201);
    }

    public function show(Presentacion $presentacion): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $presentacion,
            'message' => 'Registro de presentación obtenido',
        ]);
    }

    public function update(SavePresentacionRequest $request, Presentacion $presentacion): JsonResponse
    {
        $presentacion->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $presentacion->refresh(),
            'message' => 'Registro de presentación actualizado',
        ]);
    }

    public function destroy(Presentacion $presentacion): JsonResponse
    {
        $presentacion->update(['activo' => false]);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Registro de presentación desactivado',
        ]);
    }
}
