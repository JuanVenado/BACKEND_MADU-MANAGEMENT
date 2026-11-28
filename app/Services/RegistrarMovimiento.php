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
            if (! $producto->activo) {
                throw ValidationException::withMessages([
                    'producto' => ['El producto está desactivado.'],
                ]);
            }

            $asignacion = ProductoPresentacion::query()
                ->whereKey($datos['producto_presentacion'])
                ->where('producto', $producto->getKey())
                ->first();

            if ($asignacion === null) {
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

            // Multiplica centésimas por un factor de cuatro decimales y redondea a centésimas.
            $factor = (int) round($asignacion->factor_conversion * 10_000);
            $cantidadBase = intdiv($cantidad * $factor + ($cantidad < 0 ? -5_000 : 5_000), 10_000);
            if ($cantidadBase === 0) {
                throw ValidationException::withMessages([
                    'cantidad' => ['La cantidad convertida debe ser de al menos 0.01 unidades base.'],
                ]);
            }

            $ultimoSaldo = $producto->movimientos()->latest('id')->value('saldo_posterior');
            $saldoAnterior = (int) round(($ultimoSaldo ?? 0) * 100);
            $variacion = $datos['tipo'] === 'salida' ? -$cantidadBase : $cantidadBase;
            $saldoPosterior = $saldoAnterior + $variacion;

            if ($saldoPosterior < 0 || $saldoPosterior > 9999999999) {
                throw ValidationException::withMessages([
                    'cantidad' => ['El saldo resultante está fuera del rango permitido.'],
                ]);
            }

            $datos['cantidad_base'] = $this->decimal($cantidadBase);
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
