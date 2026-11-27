<?php

namespace Tests\Feature;

use App\Models\Conversacion;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermisosTest extends TestCase
{
    use RefreshDatabase;

    // ─── helpers ───────────────────────────────────────────────────────────

    private function empresa(string $nombre = 'Test SA'): Empresa
    {
        return Empresa::create(['nombre_empresa' => $nombre, 'activo' => true]);
    }

    private function cliente(Empresa $empresa): User
    {
        return User::factory()->create(['id_empresa' => $empresa->id_empresa]);
    }

    private function proyecto(User $usuario, Empresa $empresa): Proyecto
    {
        $p = new Proyecto();
        $p->id_usuario       = $usuario->id_usuario;
        $p->id_empresa       = $empresa->id_empresa;
        $p->nombre_proyecto  = 'Proyecto test';
        $p->carpeta_archivos = 'proj_test001';
        $p->estado           = 'Pendiente';
        $p->save();
        return $p;
    }

    // ─── Step 1a: UsuarioController admin-only ─────────────────────────────

    public function test_cliente_no_puede_editar_otro_usuario(): void
    {
        $empresa = $this->empresa();
        $cliente = $this->cliente($empresa);
        $otro    = $this->cliente($empresa);

        $this->actingAs($cliente)
            ->patch("/usuarios/{$otro->id_usuario}", [
                'nombre'    => 'Hacker',
                'apellidos' => 'Malo',
                'email'     => $otro->email,
                'activo'    => 1,
            ])
            ->assertStatus(403);
    }

    public function test_cliente_no_puede_eliminar_usuario(): void
    {
        $empresa = $this->empresa();
        $cliente = $this->cliente($empresa);
        $otro    = $this->cliente($empresa);

        $this->actingAs($cliente)
            ->delete("/usuarios/{$otro->id_usuario}")
            ->assertStatus(403);
    }

    public function test_admin_puede_editar_usuario(): void
    {
        $admin   = User::factory()->admin()->create();
        $empresa = $this->empresa();
        $usuario = $this->cliente($empresa);

        $this->actingAs($admin)
            ->patch("/usuarios/{$usuario->id_usuario}", [
                'nombre'    => 'Editado',
                'apellidos' => 'Correcto',
                'email'     => $usuario->email,
                'activo'    => 1,
            ])
            ->assertRedirect();
    }

    // ─── Step 1b: rutas de visibilidad solo admin ──────────────────────────

    public function test_cliente_no_puede_toglear_visibilidad_archivo(): void
    {
        $empresa  = $this->empresa();
        $cliente  = $this->cliente($empresa);
        $proyecto = $this->proyecto($cliente, $empresa);

        $this->actingAs($cliente)
            ->patch("/proyectos/{$proyecto->id_proyecto}/archivos/toggle", [
                'nombre_archivo' => 'plano.pdf',
            ])
            ->assertStatus(403);
    }

    public function test_cliente_no_puede_programar_visibilidad_masiva(): void
    {
        $empresa  = $this->empresa();
        $cliente  = $this->cliente($empresa);
        $proyecto = $this->proyecto($cliente, $empresa);

        $this->actingAs($cliente)
            ->post("/proyectos/{$proyecto->id_proyecto}/archivos/programar", [
                'archivos_seleccionados' => ['plano.pdf'],
                'fecha_ocultacion'       => now()->addDay()->toDateTimeString(),
            ])
            ->assertStatus(403);
    }

    // ─── Step 1c: subirArchivo admin + validación ──────────────────────────

    public function test_cliente_no_puede_subir_archivos(): void
    {
        Storage::fake('proyectos');
        $empresa  = $this->empresa();
        $cliente  = $this->cliente($empresa);
        $proyecto = $this->proyecto($cliente, $empresa);

        $this->actingAs($cliente)
            ->post("/proyectos/{$proyecto->id_proyecto}/archivos", [
                'archivos' => [UploadedFile::fake()->create('plano.pdf', 10, 'application/pdf')],
            ])
            ->assertStatus(403);
    }

    public function test_admin_no_puede_subir_archivo_de_extension_no_permitida(): void
    {
        Storage::fake('proyectos');
        $admin    = User::factory()->admin()->create();
        $empresa  = $this->empresa();
        $cliente  = $this->cliente($empresa);
        $proyecto = $this->proyecto($cliente, $empresa);

        $this->actingAs($admin)
            ->post("/proyectos/{$proyecto->id_proyecto}/archivos", [
                'archivos' => [UploadedFile::fake()->create('script.exe', 10, 'application/x-msdownload')],
            ])
            ->assertSessionHasErrors('archivos.0');
    }

    // ─── Step 1d: descargarArchivo puedeVerProyecto + visible ─────────────

    public function test_cliente_de_otra_empresa_no_puede_descargar(): void
    {
        Storage::fake('proyectos');
        $empresaA  = $this->empresa('Empresa A');
        $empresaB  = $this->empresa('Empresa B');
        $usuarioB  = $this->cliente($empresaB);
        $proyectoB = $this->proyecto($usuarioB, $empresaB);
        $clienteA  = $this->cliente($empresaA);

        $this->actingAs($clienteA)
            ->get("/proyectos/{$proyectoB->id_proyecto}/archivos/plano.pdf")
            ->assertStatus(403);
    }

    public function test_cliente_no_puede_descargar_archivo_oculto(): void
    {
        Storage::fake('proyectos');
        $empresa  = $this->empresa();
        $cliente  = $this->cliente($empresa);
        $proyecto = $this->proyecto($cliente, $empresa);

        Storage::disk('proyectos')->put(
            $proyecto->rutaCarpeta() . '/plano.pdf',
            'contenido fake'
        );

        Documento::create([
            'id_proyecto'    => $proyecto->id_proyecto,
            'nombre_archivo' => 'plano.pdf',
            'visible'        => false,
        ]);

        $this->actingAs($cliente)
            ->get("/proyectos/{$proyecto->id_proyecto}/archivos/plano.pdf")
            ->assertStatus(404);
    }

    // ─── Step 1e: ChatController store ownership ───────────────────────────

    public function test_cliente_no_puede_escribir_en_conversacion_ajena(): void
    {
        $empresa  = $this->empresa();
        $clienteA = $this->cliente($empresa);
        $clienteB = $this->cliente($empresa);

        $conv = Conversacion::create([
            'id_usuario' => $clienteB->id_usuario,
            'activo'     => true,
        ]);

        $this->actingAs($clienteA)
            ->post('/chat/enviar', [
                'id_conversacion' => $conv->id_conversacion,
                'contenido'       => 'mensaje intruso',
            ])
            ->assertStatus(403);
    }

    // ─── Step 1f: show no pasa empresas a no-admin ─────────────────────────

    public function test_show_usuario_no_pasa_empresas_a_cliente(): void
    {
        $empresa = $this->empresa();
        $cliente = $this->cliente($empresa);
        $otro    = $this->cliente($empresa);

        $this->actingAs($cliente)
            ->get("/usuarios/{$otro->id_usuario}")
            ->assertViewHas('empresas', fn ($col) => $col->isEmpty());
    }

    // ─── Step 2: rutaCarpeta usa carpeta_archivos ─────────────────────────

    public function test_ruta_carpeta_usa_campo_carpeta_archivos(): void
    {
        $empresa  = $this->empresa();
        $cliente  = $this->cliente($empresa);
        $proyecto = $this->proyecto($cliente, $empresa);

        $this->assertSame('proj_test001', $proyecto->rutaCarpeta());
    }

    // ─── Step 3: nombre único en subida duplicada ─────────────────────────

    public function test_subir_archivo_duplicado_genera_sufijo(): void
    {
        Storage::fake('proyectos');
        $admin    = User::factory()->admin()->create();
        $empresa  = $this->empresa();
        $cliente  = $this->cliente($empresa);
        $proyecto = $this->proyecto($cliente, $empresa);

        // Archivo ya existente
        Storage::disk('proyectos')->put(
            $proyecto->rutaCarpeta() . '/plano.pdf',
            'original'
        );

        $this->actingAs($admin)
            ->post("/proyectos/{$proyecto->id_proyecto}/archivos", [
                'archivos' => [UploadedFile::fake()->create('plano.pdf', 10, 'application/pdf')],
            ]);

        Storage::disk('proyectos')->assertExists($proyecto->rutaCarpeta() . '/plano.pdf');
        Storage::disk('proyectos')->assertExists($proyecto->rutaCarpeta() . '/plano (1).pdf');
    }
}
