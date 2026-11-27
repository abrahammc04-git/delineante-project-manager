<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProyectoEmpresaTest extends TestCase
{
    use RefreshDatabase;

    private function crearEmpresa(string $nombre = 'Empresa Test SA'): Empresa
    {
        return Empresa::create([
            'nombre_empresa' => $nombre,
            'activo'         => true,
        ]);
    }

    private function crearProyecto(User $usuario, Empresa $empresa): Proyecto
    {
        $proyecto = new Proyecto();
        $proyecto->id_usuario       = $usuario->id_usuario;
        $proyecto->id_empresa       = $empresa->id_empresa;
        $proyecto->nombre_proyecto  = 'Proyecto Empresa Test';
        $proyecto->estado           = 'Pendiente';
        $proyecto->tipo_proyecto    = 'Comercial';
        $proyecto->fecha_inicio     = now()->toDateString();
        $proyecto->carpeta_archivos = 'proj_test_' . uniqid();
        $proyecto->save();

        return $proyecto;
    }

    public function test_listado_proyectos_muestra_nombre_empresa(): void
    {
        $empresa = $this->crearEmpresa('Acme Corp');
        $admin   = User::factory()->admin()->create(['id_empresa' => $empresa->id_empresa]);
        $this->crearProyecto($admin, $empresa);

        $this->actingAs($admin)
            ->get(route('proyectos.index'))
            ->assertStatus(200)
            ->assertSee('Acme Corp');
    }

    public function test_ficha_proyecto_muestra_nombre_empresa(): void
    {
        $empresa  = $this->crearEmpresa('Vista SA');
        $admin    = User::factory()->admin()->create(['id_empresa' => $empresa->id_empresa]);
        $proyecto = $this->crearProyecto($admin, $empresa);

        $this->actingAs($admin)
            ->get(route('proyectos.show', $proyecto->id_proyecto))
            ->assertStatus(200)
            ->assertSee('Vista SA');
    }

    public function test_ficha_proyecto_sin_empresa_muestra_no_especificada(): void
    {
        $admin    = User::factory()->admin()->create();
        $proyecto = new Proyecto();
        $proyecto->id_usuario       = $admin->id_usuario;
        $proyecto->id_empresa       = null;
        $proyecto->nombre_proyecto  = 'Sin Empresa';
        $proyecto->estado           = 'Pendiente';
        $proyecto->tipo_proyecto    = 'Residencial';
        $proyecto->fecha_inicio     = now()->toDateString();
        $proyecto->carpeta_archivos = 'proj_test_noempresa';
        $proyecto->save();

        $this->actingAs($admin)
            ->get(route('proyectos.show', $proyecto->id_proyecto))
            ->assertStatus(200)
            ->assertSee('No especificada');
    }
}
