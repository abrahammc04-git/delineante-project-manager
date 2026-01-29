<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Verificar que sea admin
        if (auth()->user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        return $next($request);
    }
}