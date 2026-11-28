<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_requests_without_a_valid_token_receive_a_json_unauthorized_response(): void
    {
        $this->getJson('/api/user')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'No autenticado.',
                'error' => 'unauthenticated',
            ]);

        $this->withToken('invalid-token')
            ->getJson('/api/user')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'No autenticado.',
                'error' => 'unauthenticated',
            ]);
    }

    public function test_expired_sanctum_tokens_are_rejected(): void
    {
        $user = $this->createUsuario();
        $token = $user->createToken('test-token', ['*'], now()->subMinute())->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertUnauthorized()
            ->assertExactJson([
                'message' => 'No autenticado.',
                'error' => 'unauthenticated',
            ]);
    }

    public function test_users_without_the_administrator_role_receive_forbidden(): void
    {
        $user = $this->createUsuario('inventario');
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertForbidden()
            ->assertExactJson([
                'message' => 'No tienes permiso para realizar esta acción.',
                'error' => 'forbidden',
            ]);
    }

    public function test_inactive_administrators_receive_forbidden(): void
    {
        $user = $this->createUsuario('administrador', false);
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertForbidden()
            ->assertExactJson([
                'message' => 'La cuenta está desactivada.',
                'error' => 'inactive_account',
            ]);
    }

    public function test_active_administrators_can_access_the_protected_route(): void
    {
        $user = $this->createUsuario('administrador');
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withToken($token)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJsonPath('id', $user->id);
    }

    public function test_a_role_can_be_assigned_to_multiple_users(): void
    {
        $role = Rol::create(['nombre' => 'inventario']);
        $firstUser = $this->createUsuarioWithRole($role);
        $secondUser = $this->createUsuarioWithRole($role);

        $this->assertCount(2, $role->fresh()->usuarios);
        $this->assertSame($role->id, $firstUser->rol->id);
        $this->assertSame($role->id, $secondUser->rol->id);
    }

    private function createUsuario(string $rol = 'inventario', bool $activo = true): Usuario
    {
        $role = Rol::create(['nombre' => $rol]);

        return $this->createUsuarioWithRole($role, $activo);
    }

    private function createUsuarioWithRole(Rol $role, bool $activo = true): Usuario
    {
        $usuario = new Usuario;
        $usuario->nombre = 'Test User';
        $usuario->password = 'secret';
        $usuario->activo = $activo;
        $usuario->rol()->associate($role);
        $usuario->save();

        return $usuario;
    }
}
