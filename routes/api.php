<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\MovimientoController;
use App\Http\Controllers\Api\PresentacionController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\UnidadController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('productos', ProductoController::class);
    Route::put('/productos/{producto}/presentaciones/{presentacion}', [ProductoController::class, 'guardarPresentacion']);
    Route::apiResource('movimientos', MovimientoController::class)->only(['index', 'store', 'show']);
    Route::apiResource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);
    Route::apiResource('unidades', UnidadController::class)->parameters(['unidades' => 'unidad']);
    Route::apiResource('proveedores', ProveedorController::class)->parameters(['proveedores' => 'proveedor']);
    Route::apiResource('presentaciones', PresentacionController::class)->parameters(['presentaciones' => 'presentacion']);
});
