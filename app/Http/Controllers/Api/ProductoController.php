<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoPresentacionRequest;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Resources\ProductoPresentacionResource;
use App\Http\Resources\ProductoResource;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    private const array RELACIONES = ['categoriaRel', 'unidadRel', 'ultimoMovimiento'];

    public function index(): JsonResponse
    {
        $productos = Producto::with(self::RELACIONES)->get();

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
            'data' => new ProductoResource($producto->refresh()->load(self::RELACIONES)),
            'message' => 'Producto creado',
        ], 201);
    }

    public function show(Producto $producto): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ProductoResource($producto->load([...self::RELACIONES, 'presentacionesAsignadas.presentacion'])),
            'message' => 'Producto obtenido',
        ], 200);
    }

    public function update(StoreProductoRequest $request, Producto $producto): JsonResponse
    {
        $producto->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => new ProductoResource($producto->load(self::RELACIONES)),
            'message' => 'Producto actualizado',
        ], 200);
    }

    public function destroy(Producto $producto): JsonResponse
    {
        $producto->update(['activo' => false]);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Producto desactivado',
        ], 200);
    }

    public function guardarPresentacion(
        StoreProductoPresentacionRequest $request,
        Producto $producto,
        Presentacion $presentacion,
    ): JsonResponse {
        $asignacion = $producto->presentacionesAsignadas()->updateOrCreate(
            ['presentacion' => $presentacion->id],
            $request->validated(),
        );

        return response()->json([
            'success' => true,
            'data' => new ProductoPresentacionResource($asignacion->setRelation('presentacion', $presentacion)),
            'message' => 'Presentación del producto guardada',
        ]);
    }
}
