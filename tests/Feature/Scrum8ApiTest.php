<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Models\ProductoPresentacion;
use App\Models\Unidad;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class Scrum8ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_movimientos_update_the_balance_and_reject_invalid_stock_changes(): void
    {
        $this->authenticate();
        $producto = Producto::create([
            'nombre' => 'Agua',
            'coding' => 'AGUA',
            'categoria' => Categoria::create(['nombre' => 'Bebidas'])->id,
            'unidad' => Unidad::create(['nombre' => 'Pieza', 'abreviatura' => 'pz'])->id,
        ]);
        $presentacion = ProductoPresentacion::create([
            'producto' => $producto->id,
            'presentacion' => Presentacion::create(['nombre' => 'Individual'])->id,
        ]);
        $payload = ['producto' => $producto->id, 'producto_presentacion' => $presentacion->id];

        $entrada = $this->postJson('/api/movimientos', $payload + ['tipo' => 'entrada', 'cantidad' => 2.5])
            ->assertCreated()->assertJsonPath('data.saldo_posterior', '2.50')
            ->assertJsonPath('data.producto', 'Agua');
        $this->assertDatabaseHas('movimientos', [
            'id' => $entrada->json('data.id'),
            'cantidad_base' => 2.5,
            'saldo_anterior' => 0,
            'saldo_posterior' => 2.5,
        ]);
        $this->postJson('/api/movimientos', $payload + ['tipo' => 'salida', 'cantidad' => 3])
            ->assertBadRequest()->assertJsonStructure(['data' => ['errors' => ['cantidad']]]);
        $this->postJson('/api/movimientos', $payload + ['tipo' => 'ajuste', 'cantidad' => -0.5, 'motivo' => 'Merma'])
            ->assertCreated()->assertJsonPath('data.saldo_anterior', '2.50')
            ->assertJsonPath('data.saldo_posterior', '2.00');
        $this->getJson('/api/movimientos/'.$entrada->json('data.id'))->assertOk()
            ->assertJsonPath('data.saldo_posterior', '2.50');
    }

    public function test_product_presentations_convert_movements_and_expose_the_current_balance(): void
    {
        $this->authenticate();
        $categoria = Categoria::create(['nombre' => 'Bebidas']);
        $unidad = Unidad::create(['nombre' => 'Pieza', 'abreviatura' => 'pz']);
        $presentacion = Presentacion::create(['nombre' => 'Caja']);

        $productoId = $this->postJson('/api/productos', [
            'nombre' => 'Agua',
            'coding' => 'AGUA',
            'categoria' => $categoria->id,
            'unidad' => $unidad->id,
            'stock_min' => 5,
        ])->assertCreated()->assertJsonPath('data.saldo_actual', '0.00')->json('data.id');

        $asignacionUrl = "/api/productos/{$productoId}/presentaciones/{$presentacion->id}";
        $this->putJson($asignacionUrl, ['factor_conversion' => 0])
            ->assertBadRequest()->assertJsonStructure(['data' => ['errors' => ['factor_conversion']]]);
        $asignacionId = $this->putJson($asignacionUrl, ['factor_conversion' => 12])
            ->assertOk()->json('data.id');
        $this->putJson($asignacionUrl, ['factor_conversion' => '10.5000'])
            ->assertOk()->assertJsonPath('data.id', $asignacionId);
        $this->assertDatabaseCount('producto_presentacion', 1);

        $productoUrl = "/api/productos/{$productoId}";
        $this->getJson($productoUrl)->assertOk()
            ->assertJsonPath('data.categoria_id', $categoria->id)
            ->assertJsonPath('data.unidad_id', $unidad->id)
            ->assertJsonPath('data.presentaciones.0.id', $asignacionId)
            ->assertJsonPath('data.presentaciones.0.presentacion_id', $presentacion->id)
            ->assertJsonPath('data.presentaciones.0.factor_conversion', '10.5000');

        $movimiento = ['producto' => $productoId, 'producto_presentacion' => $asignacionId];
        $this->postJson('/api/movimientos', $movimiento + ['tipo' => 'entrada', 'cantidad' => 2])
            ->assertCreated()->assertJsonPath('data.cantidad_base', '21.00');
        $this->postJson('/api/movimientos', $movimiento + ['tipo' => 'salida', 'cantidad' => 3])
            ->assertBadRequest()->assertJsonStructure(['data' => ['errors' => ['cantidad']]]);
        $ajusteId = $this->postJson('/api/movimientos', $movimiento + [
            'tipo' => 'ajuste',
            'cantidad' => -0.25,
            'motivo' => 'Merma',
            'referencia' => 'REV-01',
        ])->assertCreated()
            ->assertJsonPath('data.cantidad_base', '-2.63')
            ->assertJsonPath('data.saldo_posterior', '18.37')
            ->json('data.id');

        $this->getJson('/api/productos')->assertOk()
            ->assertJsonPath('data.0.saldo_actual', '18.37')
            ->assertJsonPath('data.0.stock_min', 5);
        $this->deleteJson($productoUrl)->assertOk();
        $this->postJson('/api/movimientos', $movimiento + ['tipo' => 'entrada', 'cantidad' => 1])
            ->assertBadRequest()->assertJsonPath('data.errors.producto.0', 'El producto está desactivado.');
        $this->getJson('/api/movimientos/'.$ajusteId)->assertOk()
            ->assertJsonPath('data.motivo', 'Merma')
            ->assertJsonPath('data.referencia', 'REV-01');
    }

    public function test_api_hides_internal_error_details_even_in_debug_mode(): void
    {
        config(['app.debug' => true]);
        Route::get('/api/test-error', static function (): never {
            throw new RuntimeException('Detalle interno de prueba');
        });

        $this->get('/api/test-error')->assertInternalServerError()->assertExactJson([
            'success' => false,
            'data' => null,
            'message' => 'Error interno del servidor.',
        ]);
    }

    public function test_product_endpoints_and_logout_use_the_api_contract(): void
    {
        $this->postJson('/api/login', [])->assertBadRequest()
            ->assertJsonStructure(['data' => ['errors' => ['nombre', 'password']]]);
        $this->authenticate();

        $endpoint = '/api/productos';
        $payload = [
            'nombre' => 'Agua',
            'coding' => 'AGUA',
            'categoria' => Categoria::create(['nombre' => 'Bebidas'])->id,
            'unidad' => Unidad::create(['nombre' => 'Pieza', 'abreviatura' => 'pz'])->id,
        ];
        $id = $this->postJson($endpoint, $payload)->assertCreated()->json('data.id');
        $this->postJson($endpoint, $payload)->assertBadRequest()
            ->assertJsonStructure(['data' => ['errors' => ['coding']]]);
        $this->getJson($endpoint)->assertOk()->assertJsonPath('data.0.id', $id);
        $this->putJson($endpoint.'/'.$id, ['stock_min' => 10] + $payload)->assertOk()
            ->assertJsonPath('data.stock_min', 10);
        $this->getJson($endpoint.'/'.$id)->assertOk()->assertJsonPath('data.nombre', 'Agua');
        $this->deleteJson($endpoint.'/'.$id)->assertOk()->assertJsonPath('data', null);
        $this->getJson($endpoint.'/'.$id)->assertOk()->assertJsonPath('data.activo', false);

        $this->postJson('/api/logout')->assertOk()->assertJsonPath('data', null);
        $this->app['auth']->forgetGuards();
        $this->getJson($endpoint)->assertUnauthorized();
    }

    public function test_movimientos_only_expose_supported_actions(): void
    {
        $this->get('/api/productos')->assertUnauthorized()->assertJsonPath('success', false);
        $this->authenticate();

        $this->patchJson('/api/movimientos/1', [])->assertStatus(405)
            ->assertJsonPath('success', false);
        $this->deleteJson('/api/movimientos/1')->assertStatus(405)
            ->assertJsonPath('success', false);
    }

    private function authenticate(): void
    {
        Usuario::create(['nombre' => 'operador', 'password' => 'password']);
        $login = $this->postJson('/api/login', ['nombre' => 'operador', 'password' => 'password'])
            ->assertOk()->assertJsonPath('success', true);

        $this->withToken($login->json('data.token'));
    }
}
