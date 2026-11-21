<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Categoria::all(),
            'message' => 'Listado de categorias',
        ]);
    }

    public function store(SaveCategoriaRequest $request): JsonResponse
    {
        $categoria = Categoria::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $categoria->refresh(),
            'message' => 'Registro de categoría creado',
        ], 201);
    }

    public function show(Categoria $categoria): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $categoria,
            'message' => 'Registro de categoría obtenido',
        ]);
    }

    public function update(SaveCategoriaRequest $request, Categoria $categoria): JsonResponse
    {
        $categoria->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $categoria->refresh(),
            'message' => 'Registro de categoría actualizado',
        ]);
    }

    public function destroy(Categoria $categoria): JsonResponse
    {
        $categoria->update(['activo' => false]);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Registro de categoría desactivado',
        ]);
    }
}
