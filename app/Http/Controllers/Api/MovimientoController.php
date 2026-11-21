<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovimientoRequest;
use App\Http\Resources\MovimientoResource;
use App\Models\Movimiento;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class MovimientoController extends Controller
{
    public function index(): JsonResponse
    {
        $movimientos = Movimiento::with(['producto', 'usuario'])->latest()->get();
        return response()->json(['success' => true, 'data' => MovimientoResource::collection($movimientos), 'message' => 'Lista de movimientos'], 200);
    }

    public function store(StoreMovimientoRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $datos = $request->validated();
            $datos['usuario'] = $request->user()->id;
            if (empty($datos['folio'])) {
                $datos['folio'] = 'MOV-' . now()->format('YmdHis') . '-' . rand(100, 999);
            }
            $movimiento = Movimiento::create($datos);
            DB::commit();
            return response()->json(['success' => true, 'data' => new MovimientoResource($movimiento), 'message' => 'Movimiento registrado'], 201);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'data' => null, 'message' => 'Error interno al registrar el movimiento'], 500);
        }
    }

    public function show(Movimiento $movimiento): JsonResponse
    {
        return response()->json(['success' => true, 'data' => new MovimientoResource($movimiento->load(['producto', 'usuario'])), 'message' => 'Movimiento obtenido'], 200);
    }
}