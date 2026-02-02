<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proyecto;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Query base
        $q = Proyecto::query();

        // 🔐 Si es cliente, solo sus proyectos.
        // ⚠️ AJUSTA el campo según tu BD:
        // - si en proyectos tienes id_usuario (FK a usuarios) -> usa where('id_usuario', $user->id_usuario)
        // - si tienes cliente_id -> where('cliente_id', $user->id_usuario)
        if ($user->rol !== 'admin') {
            $q->where('id_usuario', $user->id_usuario); // <-- AJUSTA si tu FK se llama distinto
        }

        // Estadísticas por estado (AJUSTA los valores si en BD usáis otros)
        $total = (clone $q)->count();

        $enProceso = (clone $q)->where('estado', 'en_proceso')->count();
        $completados = (clone $q)->where('estado', 'completado')->count();
        $pendientes = (clone $q)->where('estado', 'pendiente')->count();

        $porcentajeCompletados = $total > 0
            ? round(($completados / $total) * 100)
            : 0;


        // Últimos proyectos (tabla)
        $proyectosRecientes = (clone $q)
            ->with('usuario')
            ->orderByDesc('ultima_actualizacion')
            ->limit(5)
            ->get();


        return view('dashboard', compact(
            'total',
            'enProceso',
            'completados',
            'pendientes',
            'porcentajeCompletados',
            'proyectosRecientes'
        ));
    }
}
