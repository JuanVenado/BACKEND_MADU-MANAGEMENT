<?php

namespace App\Services;

use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\ProductoPresentacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegistrarMovimiento
{
    public function registrar(array $datos, int $usuarioId): Movimiento
    {
        return DB::transaction(function () use ($datos, $usuarioId): Movimiento {
            $producto = Producto::query()->lockForUpdate()->findOrFail($datos['producto']);
            $presentacionValida = ProductoPresentacion::query()
                ->whereKey($datos['producto_presentacion'])
                ->where('producto', $producto->getKey())
                ->exists();

            if (!$presentacionValida) {
                throw ValidationException::withMessages([
                    'producto_presentacion' => ['La presentación no pertenece al producto.'],
                ]);
            }

            $cantidad = (int) round($datos['cantidad'] * 100);
            if ($cantidad === 0 || ($cantidad < 0 && $datos['tipo'] !== 'ajuste')) {
                throw ValidationException::withMessages([
                    'cantidad' => ['La cantidad debe ser positiva para entradas y salidas, y distinta de cero para ajustes.'],
                ]);
            }

            $ultimoSaldo = $producto->movimientos()->latest('id')->value('saldo_posterior');
            $saldoAnterior = (int) round(($ultimoSaldo ?? 0) * 100);
            $variacion = $datos['tipo'] === 'salida' ? -$cantidad : $cantidad;
            $saldoPosterior = $saldoAnterior + $variacion;

            if ($saldoPosterior < 0 || $saldoPosterior > 9999999999) {
                throw ValidationException::withMessages([
                    'cantidad' => ['El saldo resultante está fuera del rango permitido.'],
                ]);
            }

            // La relación producto-presentación todavía no almacena una equivalencia.
            $datos['cantidad_base'] = $this->decimal($cantidad);
            $datos['saldo_anterior'] = $this->decimal($saldoAnterior);
            $datos['saldo_posterior'] = $this->decimal($saldoPosterior);
            $datos['usuario'] = $usuarioId;
            $datos['folio'] = $datos['folio'] ?? 'MOV-'.Str::ulid();

            return Movimiento::create($datos);
        }, 3);
    }

    private function decimal(int $centesimas): string
    {
        return number_format($centesimas / 100, 2, '.', '');
    }
}
