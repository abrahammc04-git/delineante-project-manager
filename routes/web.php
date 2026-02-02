<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;




// Redirigir raíz a login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard // ya hay controlador, hecho por Moi jeje
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // RUTAS DE ARCHIVOS
    Route::post('/proyectos/{id}/archivos', [App\Http\Controllers\ProyectoController::class, 'subirArchivo'])->name('proyectos.archivos.subir');
    Route::get('/proyectos/{id}/archivos/{nombreArchivo}', [App\Http\Controllers\ProyectoController::class, 'descargarArchivo'])->name('proyectos.archivos.descargar');
    Route::delete('/proyectos/{id}/archivos/{nombreArchivo}', [App\Http\Controllers\ProyectoController::class, 'eliminarArchivo'])->name('proyectos.archivos.eliminar');
});

Route::middleware(['auth'])->group(function () {
    // Esto genera automáticamente las 7 rutas (index, create, store, show, edit, update, destroy)
    Route::resource('proyectos', ProyectoController::class);
    Route::patch('/proyectos/{id}/archivos/toggle', [App\Http\Controllers\ProyectoController::class, 'toggleVisibilidad'])->name('proyectos.archivos.toggle');
    Route::post('/proyectos/{id}/archivos/programar', [App\Http\Controllers\ProyectoController::class, 'programarMasivo'])->name('proyectos.archivos.programar');
    Route::patch('/proyectos/{id}/archivos/cancelar-programacion', [App\Http\Controllers\ProyectoController::class, 'cancelarProgramacion'])->name('proyectos.archivos.cancelar');
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
});


// Incluir rutas de autenticación
require __DIR__.'/auth.php';