<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovimientoRequest;
use App\Http\Resources\MovimientoResource;
use App\Models\Movimiento;
use App\Services\RegistrarMovimiento;
use Illuminate\Http\JsonResponse;

class MovimientoController extends Controller
{
    public function index(): JsonResponse
    {
        $movimientos = Movimiento::with(['producto', 'usuario'])->latest()->get();
        return response()->json(['success' => true, 'data' => MovimientoResource::collection($movimientos), 'message' => 'Lista de movimientos'], 200);
    }

    public function store(StoreMovimientoRequest $request, RegistrarMovimiento $registrador): JsonResponse
    {
        $movimiento = $registrador->registrar($request->validated(), $request->user()->id);

        return response()->json([
            'success' => true,
            'data' => new MovimientoResource($movimiento->load(['producto', 'usuario'])),
            'message' => 'Movimiento registrado',
        ], 201);
    }

    public function show(Movimiento $movimiento): JsonResponse
    {
        return response()->json(['success' => true, 'data' => new MovimientoResource($movimiento->load(['producto', 'usuario'])), 'message' => 'Movimiento obtenido'], 200);
    }
}
