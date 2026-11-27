<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnlaceEmpresasTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_ve_enlace_empresas_en_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee(route('empresas.index'));
    }

    public function test_cliente_no_ve_enlace_empresas_en_dashboard(): void
    {
        $empresa = Empresa::create(['nombre_empresa' => 'Test SA', 'activo' => true]);
        $cliente = User::factory()->create(['id_empresa' => $empresa->id_empresa]);

        $this->actingAs($cliente)
            ->get(route('dashboard'))
            ->assertStatus(200)
            ->assertDontSee(route('empresas.index'));
    }
}
