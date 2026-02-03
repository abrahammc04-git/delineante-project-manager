<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->where('rol', 'cliente'); // listamos clientes (no admins)

        if ($request->filled('nombre')) {
            $nombre = $request->input('nombre');
            $query->where(function ($q) use ($nombre) {
                $q->where('nombre', 'like', "%{$nombre}%")
                    ->orWhere('apellidos', 'like', "%{$nombre}%");
            });
        }

        if ($request->filled('empresa')) {
            $query->where('empresa', 'like', '%' . $request->empresa . '%');
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
        return view('usuarios.show', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
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
            'empresa' => ['nullable', 'string', 'max:255'],
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
        $authUser = Auth::user();

        // Si por lo que sea no hay usuario autenticado, fuera
        if (!$authUser) {
            abort(403);
        }

        // Evitar que se borre a sí mismo
        if ((int) $authUser->id_usuario === (int) $usuario->id_usuario) {
            return back()->with('error', 'No puedes eliminar tu propio usuario desde aquí.');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }
}
