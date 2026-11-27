<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpresaCrudTest extends TestCase
{
    use RefreshDatabase;

    // ─── admin puede crear una empresa ────────────────────────────────────

    public function test_admin_puede_ver_formulario_crear_empresa(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('empresas.create'))
            ->assertStatus(200)
            ->assertSee('Nueva Empresa');
    }

    public function test_admin_puede_crear_empresa(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('empresas.store'), [
                'nombre_empresa' => 'Constructora XYZ S.L.',
                'cif'            => 'B87654321',
                'telefono'       => '912345678',
                'email_contacto' => 'info@xyz.com',
                'direccion'      => 'Calle Falsa 123, Madrid',
                'activo'         => '1',
            ])
            ->assertRedirect(route('empresas.index'));

        $this->assertDatabaseHas('empresas', ['nombre_empresa' => 'Constructora XYZ S.L.']);
    }

    public function test_crear_empresa_requiere_nombre(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('empresas.store'), ['nombre_empresa' => ''])
            ->assertSessionHasErrors('nombre_empresa');
    }

    // ─── admin puede editar una empresa ───────────────────────────────────

    public function test_admin_puede_ver_formulario_editar_empresa(): void
    {
        $admin   = User::factory()->admin()->create();
        $empresa = Empresa::create([
            'nombre_empresa' => 'Empresa Original',
            'activo'         => true,
        ]);

        $this->actingAs($admin)
            ->get(route('empresas.edit', $empresa->id_empresa))
            ->assertStatus(200)
            ->assertSee('Editar Empresa')
            ->assertSee('Empresa Original');
    }

    public function test_admin_puede_editar_empresa(): void
    {
        $admin   = User::factory()->admin()->create();
        $empresa = Empresa::create([
            'nombre_empresa' => 'Nombre Viejo',
            'activo'         => true,
        ]);

        $this->actingAs($admin)
            ->put(route('empresas.update', $empresa->id_empresa), [
                'nombre_empresa' => 'Nombre Nuevo',
                'cif'            => 'A11111111',
                'activo'         => '1',
            ])
            ->assertRedirect(route('empresas.index'));

        $this->assertDatabaseHas('empresas', ['nombre_empresa' => 'Nombre Nuevo']);
        $this->assertDatabaseMissing('empresas', ['nombre_empresa' => 'Nombre Viejo']);
    }

    // ─── no-admin no puede acceder ────────────────────────────────────────

    public function test_cliente_no_puede_acceder_al_formulario_de_empresa(): void
    {
        $empresa = Empresa::create(['nombre_empresa' => 'Test', 'activo' => true]);
        $cliente = User::factory()->create(['id_empresa' => $empresa->id_empresa]);

        $this->actingAs($cliente)
            ->get(route('empresas.create'))
            ->assertStatus(403);
    }

    public function test_cliente_no_puede_editar_empresa(): void
    {
        $empresa = Empresa::create(['nombre_empresa' => 'Test', 'activo' => true]);
        $cliente = User::factory()->create(['id_empresa' => $empresa->id_empresa]);

        $this->actingAs($cliente)
            ->put(route('empresas.update', $empresa->id_empresa), [
                'nombre_empresa' => 'Hackeado',
                'activo'         => '1',
            ])
            ->assertStatus(403);
    }
}
