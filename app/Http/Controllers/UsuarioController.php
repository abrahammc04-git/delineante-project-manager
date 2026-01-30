<?php

namespace App\Http\Controllers;

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
            $query->where('empresa', 'like', '%'.$request->empresa.'%');
        }

        if ($request->filled('telefono')) {
            $query->where('telefono', 'like', '%'.$request->telefono.'%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%'.$request->email.'%');
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
}

