<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorizacionTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────

    private function crearEmpresa(string $nombre = 'Empresa Test'): Empresa
    {
        return Empresa::create([
            'nombre_empresa' => $nombre,
            'activo'         => true,
        ]);
    }

    private function crearProyectoEnEmpresa(User $propietario, Empresa $empresa): Proyecto
    {
        $proyecto = new Proyecto();
        $proyecto->id_usuario      = $propietario->id_usuario;
        $proyecto->id_empresa      = $empresa->id_empresa;
        $proyecto->nombre_proyecto = 'Proyecto de ' . $empresa->nombre_empresa;
        $proyecto->estado          = 'Pendiente';
        $proyecto->save();

        return $proyecto;
    }

    // ─────────────────────────────────────────────────────────────────
    // Test 1: un cliente no puede ver un proyecto de otra empresa
    // ─────────────────────────────────────────────────────────────────

    public function test_cliente_no_puede_ver_proyecto_de_otra_empresa(): void
    {
        $empresaA = $this->crearEmpresa('Empresa A');
        $empresaB = $this->crearEmpresa('Empresa B');

        // Cliente que pertenece a empresa A
        $clienteA = User::factory()->create(['id_empresa' => $empresaA->id_empresa]);

        // Propietario del proyecto en empresa B (distinto al cliente A)
        $usuarioB = User::factory()->create(['id_empresa' => $empresaB->id_empresa]);

        // Proyecto que pertenece a empresa B
        $proyectoB = $this->crearProyectoEnEmpresa($usuarioB, $empresaB);

        $response = $this->actingAs($clienteA)
            ->get("/proyectos/{$proyectoB->id_proyecto}");

        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────
    // Test 2: un cliente recibe 403 en POST /chatbot/consulta
    // ─────────────────────────────────────────────────────────────────

    public function test_cliente_recibe_403_en_chatbot(): void
    {
        $empresa = $this->crearEmpresa();
        $cliente = User::factory()->create(['id_empresa' => $empresa->id_empresa]);

        $response = $this->actingAs($cliente)
            ->post('/chatbot/consulta', ['mensaje' => 'dame los proyectos']);

        $response->assertStatus(403);
    }

    // ─────────────────────────────────────────────────────────────────
    // Test 3: un admin sí puede acceder a la lista de empresas
    // ─────────────────────────────────────────────────────────────────

    public function test_admin_puede_acceder_a_lista_de_empresas(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/empresas');

        $response->assertStatus(200);
    }
}
