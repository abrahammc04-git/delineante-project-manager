<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Empresa; // <-- Añadido por si luego haces un create()

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // Iniciamos la consulta y cargamos la relación 'empresa'
        $query = User::query()
            ->with('empresa')
            ->where('rol', 'cliente'); // listamos clientes (no admins)

        // SEGURIDAD: Si no es admin, solo ve a los usuarios de SU empresa
        if (!$authUser->isAdmin()) {
            $query->where('id_empresa', $authUser->id_empresa);
        }

        // --- FILTROS ---

        if ($request->filled('nombre')) {
            $nombre = $request->input('nombre');
            $query->where(function ($q) use ($nombre) {
                $q->where('nombre', 'like', "%{$nombre}%")
                  ->orWhere('apellidos', 'like', "%{$nombre}%");
            });
        }

        // CAMBIO: Filtramos por el nombre de la empresa a través de la relación
        if ($request->filled('empresa')) {
            $query->whereHas('empresa', function ($q) use ($request) {
                $q->where('nombre_empresa', 'like', '%' . $request->empresa . '%');
            });
        }

        if ($request->filled('telefono')) {
            $query->where('telefono', 'like', '%' . $request->telefono . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('activo')) {
            $query->where('activo', (int) $request->activo);
        }

        $usuarios = $query
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    public function show(User $usuario)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // SEGURIDAD: Comprobar que pertenece a la misma empresa
        if (!$authUser->isAdmin() && $authUser->id_empresa !== $usuario->id_empresa) {
            abort(403, 'No tienes permiso para ver este usuario.');
        }

        return view('usuarios.show', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        // SEGURIDAD: Comprobar que pertenece a la misma empresa
        if (!$authUser->isAdmin() && $authUser->id_empresa !== $usuario->id_empresa) {
            abort(403, 'No tienes permiso para editar este usuario.');
        }

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('usuarios', 'email')->ignore($usuario->id_usuario, 'id_usuario'),
            ],
            'telefono' => ['nullable', 'string', 'max:25'],
            // CAMBIO: Sustituimos el string antiguo por el id relacional
            'id_empresa' => ['nullable', 'exists:empresas,id_empresa'],
            'activo' => ['required', 'boolean'],
        ]);

        $usuario->fill($data);
        $usuario->save();

        return redirect()
            ->route('usuarios.show', $usuario)
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        /** @var \App\Models\User $authUser */
        $authUser = Auth::user();

        if (!$authUser) {
            abort(403);
        }

        // SEGURIDAD: Comprobar que pertenece a la misma empresa
        if (!$authUser->isAdmin() && $authUser->id_empresa !== $usuario->id_empresa) {
            abort(403, 'No tienes permiso para eliminar este usuario.');
        }

        if ((int) $authUser->id_usuario === (int) $usuario->id_usuario) {
            return back()->with('error', 'No puedes eliminar tu propio usuario desde aquí.');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }
}