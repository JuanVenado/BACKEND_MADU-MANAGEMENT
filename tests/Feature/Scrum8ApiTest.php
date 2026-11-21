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
        $this->getJson($endpoint.'/'.$id)->assertNotFound();

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
