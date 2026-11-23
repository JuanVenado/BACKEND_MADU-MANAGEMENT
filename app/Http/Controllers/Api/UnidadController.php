<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUnidadRequest;
use App\Models\Unidad;
use Illuminate\Http\JsonResponse;

class UnidadController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Unidad::all(),
            'message' => 'Listado de unidades',
        ]);
    }

    public function store(SaveUnidadRequest $request): JsonResponse
    {
        $unidad = Unidad::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $unidad->refresh(),
            'message' => 'Registro de unidad creado',
        ], 201);
    }

    public function show(Unidad $unidad): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $unidad,
            'message' => 'Registro de unidad obtenido',
        ]);
    }

    public function update(SaveUnidadRequest $request, Unidad $unidad): JsonResponse
    {
        $unidad->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $unidad->refresh(),
            'message' => 'Registro de unidad actualizado',
        ]);
    }

    public function destroy(Unidad $unidad): JsonResponse
    {
        $unidad->update(['activo' => false]);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Registro de unidad desactivado',
        ]);
    }
}
