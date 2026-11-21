<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\MovimientoController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('productos', ProductoController::class);
    Route::apiResource('movimientos', MovimientoController::class)->only(['index', 'store', 'show']);
});
