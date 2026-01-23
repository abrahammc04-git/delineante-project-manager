<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProyectoController extends Controller

{
    /**
     * Listar proyectos (Admin ve todos, Cliente solo los suyos).
     */
    public function index()
    {
        $user = Auth::user();

        // Usamos el método isAdmin() que mencionaste en los requisitos
        /** @var \App\Models\User $user */
        if ($user->isAdmin()) {
            // Admin: ve todos, ordenados por fecha de creación descendente
            $proyectos = Proyecto::with('usuario')
                        ->orderBy('fecha_creacion', 'desc')
                        ->get();
        } else {
            // Cliente: usamos el Scope definido en tu modelo
            $proyectos = Proyecto::delUsuario($user->id_usuario) // Asumo que el PK del user es id_usuario, si es 'id' cámbialo aquí
                        ->orderBy('fecha_creacion', 'desc')
                        ->get();
        }

        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Formulario de creación (Solo Admin).
     */
    public function create()
    {
        $this->authorizeAdmin();

        // Obtener lista de clientes para el select
        // Ajusta 'rol' según tu tabla users (ej: where('rol', 'cliente'))
        $clientes = User::where('rol', 'cliente')->get(); 
        
        return view('proyectos.create', compact('clientes'));
    }

    /**
     * Guardar proyecto en BD (Solo Admin).
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        // 1. Validar
        $validated = $request->validate([
            'nombre_proyecto' => 'required|string|max:255',
            'id_usuario' => 'required|exists:usuarios,id_usuario',// Ojo: verifica si tu tabla se llama 'users' o 'usuarios'
            'tipo_proyecto'   => 'required|string',
            'estado'          => 'required|string',
            'descripcion'     => 'nullable|string',
            'fecha_inicio'    => 'required|date',
            'fecha_fin_prevista' => 'nullable|date|after_or_equal:fecha_inicio',
            'localizacion'    => 'nullable|string',
        ]);

        // 2. Añadir fecha de creación manual (ya que timestamps = false)
        $validated['fecha_creacion'] = now();
        // Generar nombre de carpeta único si no viene (opcional, lógica simple)
        $validated['carpeta_archivos'] = $validated['carpeta_archivos'] ?? uniqid('proj_');

        // 3. Crear
        Proyecto::create($validated);

        return redirect()->route('proyectos.index')
                         ->with('success', 'Proyecto creado correctamente.');
    }

    /**
     * Ver detalles de un proyecto.
     */
    public function show($id)
    {
        // Buscamos manualmente porque la PK es id_proyecto
        $proyecto = Proyecto::with('usuario')->where('id_proyecto', $id)->firstOrFail();

        // Verificación de seguridad:
        // Si NO es admin Y el proyecto NO es suyo -> Prohibido
        $user = Auth::user();
        /** @var \App\Models\User $user */
        if (!$user->isAdmin() && $proyecto->id_usuario !== $user->id_usuario) { // Ajusta user->id_usuario según tu modelo User
            abort(403, 'No tienes permiso para ver este proyecto.');
        }

        return view('proyectos.show', compact('proyecto'));
    }

    /**
     * Formulario de edición (Solo Admin).
     */
    public function edit($id)
    {
        $this->authorizeAdmin();

        $proyecto = Proyecto::where('id_proyecto', $id)->firstOrFail();
        $clientes = User::where('rol', 'cliente')->get();

        return view('proyectos.edit', compact('proyecto', 'clientes'));
    }

    /**
     * Actualizar proyecto (Solo Admin).
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $proyecto = Proyecto::where('id_proyecto', $id)->firstOrFail();

        $validated = $request->validate([
            'nombre_proyecto' => 'required|string|max:255',
            'id_usuario'      => 'required|exists:usuarios,id_usuario',
            'tipo_proyecto'   => 'required|string',
            'estado'          => 'required|string',
            'descripcion'     => 'nullable|string',
            'fecha_inicio'    => 'required|date',
            'fecha_fin_prevista' => 'nullable|date|after_or_equal:fecha_inicio',
            'fecha_fin_real'     => 'nullable|date|after_or_equal:fecha_inicio',
            'localizacion'    => 'nullable|string',
        ]);

        // Actualizar fecha de modificación manual
        $validated['ultima_actualizacion'] = now();

        $proyecto->update($validated);

        return redirect()->route('proyectos.index')
                         ->with('success', 'Proyecto actualizado correctamente.');
    }

    /**
     * Eliminar proyecto (Solo Admin).
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();

        $proyecto = Proyecto::where('id_proyecto', $id)->firstOrFail();
        $proyecto->delete();

        return redirect()->route('proyectos.index')
                         ->with('success', 'Proyecto eliminado.');
    }

    /**
     * Helper privado para verificar admin
     */
    private function authorizeAdmin()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Acceso denegado. Se requieren permisos de administrador.');
        }
    }
}