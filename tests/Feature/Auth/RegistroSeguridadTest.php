<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroSeguridadTest extends TestCase
{
    use RefreshDatabase;

    public function test_registro_asigna_rol_cliente_por_defecto(): void
    {
        $this->post('/register', [
            'nombre'                => 'Test',
            'apellidos'             => 'User',
            'email'                 => 'test@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('cliente', $user->rol);
    }

    public function test_registro_ignora_campo_rol_enviado_por_el_usuario(): void
    {
        // Un atacante envía rol=admin en el formulario de registro
        $this->post('/register', [
            'nombre'                => 'Hacker',
            'apellidos'             => 'Test',
            'email'                 => 'hacker@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'rol'                   => 'admin',
        ]);

        $user = User::where('email', 'hacker@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame('cliente', $user->rol);
        $this->assertFalse($user->isAdmin());
    }
}
