<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    public function index(): JsonResponse
    {
        $productos = Producto::with(['categoriaRel', 'unidadRel'])->get();
        return response()->json([
            'success' => true,
            'data' => ProductoResource::collection($productos),
            'message' => 'Lista de productos',
        ], 200);
    }

    public function store(StoreProductoRequest $request): JsonResponse
    {
        $producto = Producto::create($request->validated());
        return response()->json([
            'success' => true,
            'data' => new ProductoResource($producto),
            'message' => 'Producto creado',
        ], 201);
    }

    public function show(Producto $producto): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ProductoResource($producto->load(['categoriaRel', 'unidadRel', 'proveedores', 'presentaciones'])),
            'message' => 'Producto obtenido',
        ], 200);
    }

    public function update(StoreProductoRequest $request, Producto $producto): JsonResponse
    {
        $producto->update($request->validated());
        return response()->json([
            'success' => true,
            'data' => new ProductoResource($producto),
            'message' => 'Producto actualizado',
        ], 200);
    }

    public function destroy(Producto $producto): JsonResponse
    {
        $producto->delete();
        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Producto eliminado',
        ], 200);
    }
}