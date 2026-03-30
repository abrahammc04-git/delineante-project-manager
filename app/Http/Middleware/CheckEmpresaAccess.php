<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Proyecto;

class CheckEmpresaAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Si el usuario no está logueado, fuera.
        if (!$user) {
            abort(403, 'Acceso denegado.');
        }

        // 2. Si es Admin/CEO, tiene pase VIP absoluto. Pasa siempre.
        if ($user->isAdmin()) {
            return $next($request);
        }

        // 3. Verificar si está intentando acceder a una EMPRESA concreta (ej: /empresas/5)
        // El 'empresa' viene de la URL. Si existe, verificamos que sea su empresa.
        $idEmpresaEnUrl = $request->route('empresa');
        if ($idEmpresaEnUrl && $user->id_empresa != $idEmpresaEnUrl) {
            abort(403, 'No tienes permiso para ver datos de otras empresas.');
        }

        // 4. Verificar si está intentando acceder a un PROYECTO concreto (ej: /proyectos/12)
        $idProyectoEnUrl = $request->route('proyecto');
        if ($idProyectoEnUrl) {
            $proyecto = Proyecto::find($idProyectoEnUrl);
            
            // Usamos el helper que creamos en el modelo User en la Fase 2.2
            if ($proyecto && !$user->puedeVerProyecto($proyecto)) {
                abort(403, 'No tienes acceso a este proyecto.');
            }
        }

        // Si ha superado los controles, le abrimos la puerta
        return $next($request);
    }
}