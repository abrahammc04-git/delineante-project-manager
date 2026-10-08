<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth; // <-- Añadido para usar Auth::user()

class EmpresaController extends Controller
{
    /**
     * Helper privado para bloquear el paso si no es CEO/Admin
     */
    private function verificarCEO()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Acceso denegado. Solo el CEO puede realizar esta acción.');
        }
    }

    // ==========================================
    // MÉTODOS SOLO PARA CEO
    // ==========================================

    public function index()
    {
        $this->verificarCEO();
        
        $empresas = Empresa::all();
        return view('empresas.index', compact('empresas'));
    }

    public function create()
    {
        $this->verificarCEO();
        
        return view('empresas.create');
    }

    public function store(Request $request)
    {
        $this->verificarCEO();

        $datos = $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'cif'            => 'nullable|string|max:50',
            'direccion'      => 'nullable|string|max:255',
            'telefono'       => 'nullable|string|max:20',
            'email_contacto' => 'nullable|email|max:255',
            'activo'         => 'sometimes|boolean', 
        ]);

        $datos['activo'] = $request->has('activo');

        Empresa::create($datos);

        return redirect()->route('empresas.index')->with('success', 'Empresa creada con éxito.');
    }

    public function edit($id)
    {
        $this->verificarCEO();
        
        $empresa = Empresa::findOrFail($id);
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $this->verificarCEO();
        
        $empresa = Empresa::findOrFail($id);

        $datos = $request->validate([
            'nombre_empresa' => 'required|string|max:255',
            'cif'            => 'nullable|string|max:50',
            'direccion'      => 'nullable|string|max:255',
            'telefono'       => 'nullable|string|max:20',
            'email_contacto' => 'nullable|email|max:255',
            'activo'         => 'sometimes|boolean',
        ]);

        $datos['activo'] = $request->has('activo');

        $empresa->update($datos);

        return redirect()->route('empresas.index')->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $this->verificarCEO();
        
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return redirect()->route('empresas.index')->with('success', 'Empresa eliminada por completo.');
    }

    // ==========================================
    // MÉTODOS MIXTOS (CEO Y USUARIOS)
    // ==========================================

    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validar que el usuario sea CEO, o que pertenezca a la empresa que intenta ver
        if (!$user->isAdmin() && $user->id_empresa !== $empresa->id_empresa) {
            abort(403, 'No tienes permiso para ver los datos de una empresa a la que no perteneces.');
        }

        // Cargamos los proyectos y los usuarios asociados para mostrarlos en la vista
        $empresa->load(['proyectos', 'usuarios']);

        return view('empresas.show', compact('empresa'));
    }
}