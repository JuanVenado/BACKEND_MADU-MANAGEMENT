<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProveedorRequest;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;

class ProveedorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Proveedor::all(),
            'message' => 'Listado de proveedores',
        ]);
    }

    public function store(SaveProveedorRequest $request): JsonResponse
    {
        $proveedor = Proveedor::create($request->validated());

        return response()->json([
            'success' => true,
            'data' => $proveedor->refresh(),
            'message' => 'Registro de proveedor creado',
        ], 201);
    }

    public function show(Proveedor $proveedor): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $proveedor,
            'message' => 'Registro de proveedor obtenido',
        ]);
    }

    public function update(SaveProveedorRequest $request, Proveedor $proveedor): JsonResponse
    {
        $proveedor->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $proveedor->refresh(),
            'message' => 'Registro de proveedor actualizado',
        ]);
    }

    public function destroy(Proveedor $proveedor): JsonResponse
    {
        $proveedor->update(['activo' => false]);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Registro de proveedor desactivado',
        ]);
    }
}
