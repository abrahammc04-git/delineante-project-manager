<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Documento;

class ProyectoController extends Controller

{
    /**
     * Listar proyectos (Admin ve todos, Cliente solo los suyos).
     */
    public function index(Request $request)
    {
        // CAMBIO: Usamos Auth::user() que tu editor reconoce mejor
        $user = Auth::user();

        // 1. Iniciamos la consulta
        $query = Proyecto::with('usuario');

        // 2. SEGURIDAD: Si NO es Admin, filtrar solo sus proyectos
        /** @var \App\Models\User $user */
        if (! $user->isAdmin()) {
            $query->where('id_usuario', $user->id_usuario);
        }

        // --- FILTROS DE BÚSQUEDA ---

        // A. Búsqueda por texto
        if ($request->filled('search')) {
            $search = $request->search;
            
            // Pasamos $user dentro del 'use' para usarlo dentro
            $query->where(function($q) use ($search, $user) {
                    // 1. TODOS buscan por nombre de proyecto
                    $q->where('nombre_proyecto', 'like', "%{$search}%");

                    // 2. SOLO EL ADMIN entra en este bloque para buscar por cliente
                    if ($user->isAdmin()) {
                        $q->orWhereHas('usuario', function($qUser) use ($search) {
                            $qUser->where('nombre', 'like', "%{$search}%")
                              ->orWhere('apellidos', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            });
        }

        // B. Filtrar por Estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // C. Filtrar por Fecha Inicio
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('fecha_inicio', '>=', $request->fecha_inicio);
        }

        // --- ORDENACIÓN ---
        
        $sort = $request->input('sort', 'fecha_creacion'); 
        $direction = $request->input('direction', 'desc');

        $allowedSorts = ['nombre_proyecto', 'estado', 'fecha_inicio', 'fecha_creacion', 'id_usuario'];
        
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        // 3. Ejecutar consulta
        $proyectos = $query->get();

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
            'localizacion'    => 'nullable|string',] ,[

            'fecha_fin_prevista.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            
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
    $proyecto = Proyecto::where('id_proyecto', $id)->firstOrFail();
        
        // Generamos la ruta. 
        // IMPORTANTE: Verifica en tu método 'subirArchivo' cómo creas la carpeta.
        // Si allí usas Str::slug, aquí también.
        $slug = Str::slug($proyecto->nombre_proyecto);
        $rutaCarpeta = "proyecto_{$slug}"; 

        $archivosInfo = [];

        // Usamos el disco 'proyectos' que configuramos antes
        if (Storage::disk('proyectos')->exists($rutaCarpeta)) {
            $files = Storage::disk('proyectos')->files($rutaCarpeta);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        foreach ($files as $file) {
            $nombreArchivo = basename($file);
            
            // BUSCAR O CREAR REGISTRO EN BD (Sincronización automática)
            // Si subiste archivos antes de tener la BD, esto crea el registro al vuelo.
            $doc = Documento::firstOrCreate(
                ['id_proyecto' => $id, 'nombre_archivo' => $nombreArchivo],
                ['visible' => false]
            );

            // Si no eres admin y está oculto, no lo añadimos a la lista
            if (!$user->isAdmin() && !$doc->visible) {
                continue;
            }

            $archivosInfo[] = [
                'nombre'  => $nombreArchivo,
                'size'    => round(Storage::disk('proyectos')->size($file) / 1024, 2),
                'fecha'   => date('d/m/Y H:i', Storage::disk('proyectos')->lastModified($file)),
                // Datos de la BD
                'visible' => $doc->visible,
                'programado' => $doc->fecha_ocultacion,
                'programado_mostrar' => $doc->fecha_publicacion
            ];
        }
    }

    return view('proyectos.show', [
    'proyecto' => $proyecto,
    'archivos' => $archivosInfo // <--- AQUÍ RENOMBRAMOS LA VARIABLE PARA LA VISTA
    ]);
    
    }

// 1. Alternar Visibilidad (Ojo)
public function toggleVisibilidad(Request $request, $id)
{
    $nombreArchivo = $request->input('nombre_archivo');
    $doc = Documento::where('id_proyecto', $id)->where('nombre_archivo', $nombreArchivo)->firstOrFail();
    
    $doc->visible = !$doc->visible;
    $doc->save();

    return back()->with('success', 'Visibilidad actualizada.');
}

// 2. Programación Masiva
public function programarMasivo(Request $request, $id)
{
    $request->validate([
        'archivos_seleccionados' => 'required|array',
        'fecha_ocultacion' => 'required|date|after:now',
    ],[
            // MENSAJES PERSONALIZADOS
            'archivos_seleccionados.required' => 'Por favor, selecciona al menos un archivo.',
            'fecha_ocultacion.after'          => 'Es obligatorio elegir una fecha y hora válida.'
    ,]);

    // Actualizamos todos los seleccionados de golpe
    Documento::where('id_proyecto', $id)
        ->whereIn('nombre_archivo', $request->archivos_seleccionados)
        ->update(['fecha_ocultacion' => $request->fecha_ocultacion]);

    return back()->with('success', 'Archivos programados para ocultarse.');
}

// Método para cancelar la programación (quitar el reloj)
    public function cancelarProgramacion(Request $request, $id)
    {
        $nombreArchivo = $request->input('nombre_archivo');
        
        $doc = Documento::where('id_proyecto', $id)
                        ->where('nombre_archivo', $nombreArchivo)
                        ->firstOrFail();

        $doc->fecha_ocultacion = null; // Borramos la fecha
        $doc->save();

        return back()->with('success', 'Programación ocultar archivos cancelada.');
    }

    public function programarPublicacion(Request $request, $id)
    {
        $request->validate([
            'archivos_seleccionados' => 'required|array',
            'fecha_publicacion'      => 'required|date|after:now',
        ], [
            'archivos_seleccionados.required' => 'Selecciona al menos un archivo para mostrar.',
            'fecha_publicacion.required'      => 'Debes elegir una fecha y hora.',
            'fecha_publicacion.after'         => 'La fecha de publicación debe ser futura.',
        ]);

        $nombres = $request->input('archivos_seleccionados');
        $fecha   = $request->input('fecha_publicacion');

        Documento::where('id_proyecto', $id)
            ->whereIn('nombre_archivo', $nombres)
            ->update([
                'fecha_publicacion' => $fecha,
                // Aseguramos que siga oculto hasta que llegue la fecha
                'visible' => false 
            ]);

        return back()->with('success', 'Se ha programado la publicación automática de los archivos seleccionados.');
    }

    public function cancelarPublicacion(Request $request, $id)
    {
        $request->validate([
            'nombre_archivo' => 'required|string',
        ]);

        $nombreArchivo = $request->input('nombre_archivo');

        // Buscamos el archivo y le quitamos la fecha de publicación
        Documento::where('id_proyecto', $id)
            ->where('nombre_archivo', $nombreArchivo)
            ->update(['fecha_publicacion' => null]); // <--- ESTA ES LA CLAVE

        return back()->with('success', 'Se ha cancelado la programación de visualización.');
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
            'localizacion'    => 'nullable|string',] ,[

            'fecha_fin_prevista.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            
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

    // --- GESTIÓN DE ARCHIVOS ---

    public function subirArchivo(Request $request, $id)
    {
        $proyecto = Proyecto::where('id_proyecto', $id)->firstOrFail();
        
        // ... (validaciones y permisos igual que antes) ...

        if ($request->hasFile('archivos')) {
            $contador = 0;
            $slug = Str::slug($proyecto->nombre_proyecto);
            $carpeta = "proyecto_{$slug}";

            foreach ($request->file('archivos') as $file) {
                $filename = $file->getClientOriginalName(); 
                
                // 1. Guardar el archivo físico
                $file->storeAs($carpeta, $filename, 'proyectos');
                
                // 2. AÑADIR ESTO: Crear/Actualizar registro en BD forzando VISIBLE = TRUE
                Documento::updateOrCreate(
                    [
                        'id_proyecto' => $id, 
                        'nombre_archivo' => $filename
                    ],
                    [
                        'visible' => false,            // <--- AQUÍ ESTÁ LA CLAVE (true = visible)
                        'fecha_ocultacion' => null    // Por si acaso resubimos uno que estaba programado
                    ]
                );

                $contador++;
            }

            return back()->with('success', "Se han subido {$contador} archivos correctamente.");
        }

        return back()->with('error', 'Error al subir los archivos.');
    }

    public function descargarArchivo($id, $nombreArchivo)
    {
        $proyecto = Proyecto::where('id_proyecto', $id)->firstOrFail();
        $user = Auth::user();

        /** @var \App\Models\User $user */
        if (!$user->isAdmin() && $proyecto->id_usuario !== $user->id_usuario) abort(403);

        // Reconstruir la ruta con la nueva lógica
        $nombreCarpeta = Str::slug($proyecto->nombre_proyecto);
        $ruta = "proyecto_{$nombreCarpeta}/{$nombreArchivo}";

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('proyectos');
        
        if (!$disk->exists($ruta)) {
            abort(404, 'Archivo no encontrado');
        }

        return $disk->download($ruta);
    }

    public function eliminarArchivo($id, $nombreArchivo)
    {
        $proyecto = Proyecto::where('id_proyecto', $id)->firstOrFail();
        $user = Auth::user();

        /** @var \App\Models\User $user */
        if (! $user->isAdmin()) {
            abort(403, 'Solo el administrador puede eliminar archivos.');
        }

        // Reconstruir la ruta
        $nombreCarpeta = Str::slug($proyecto->nombre_proyecto);
        $ruta = "proyecto_{$nombreCarpeta}/{$nombreArchivo}";
        
        if (Storage::disk('proyectos')->exists($ruta)) {
            Storage::disk('proyectos')->delete($ruta);
            return back()->with('success', 'Archivo eliminado.');
        }

        return back()->with('error', 'El archivo no existe.');
    }

}