<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;

// Redirigir raíz a login
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard (por ahora sin controlador, lo creará Roberto)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

// Incluir rutas de autenticación
require __DIR__.'/auth.php';