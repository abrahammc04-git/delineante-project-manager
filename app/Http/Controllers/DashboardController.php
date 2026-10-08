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

        // 🔐 Si es cliente, solo sus proyectos
        if ($user->rol !== 'admin') {
            $q->where('id_usuario', $user->id_usuario);
        }

        // Estadísticas por estado (con el formato exacto de la BD)
        $total = (clone $q)->count();

        // ✅ CORREGIDO: usar 'En proceso' (con espacio y mayúscula) en lugar de 'en_proceso'
        $enProceso = (clone $q)->where('estado', 'En proceso')->count();
        $completados = (clone $q)->where('estado', 'Completado')->count();
        $pendientes = (clone $q)->where('estado', 'Pendiente')->count();

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