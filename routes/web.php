<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\EmpresaController; // <-- Añadido el controlador de Empresas


// Redirigir raíz a login
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard 
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas de perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // RUTAS DE ARCHIVOS
    Route::post('/proyectos/{id}/archivos', [ProyectoController::class, 'subirArchivo'])->name('proyectos.archivos.subir');
    Route::get('/proyectos/{id}/archivos/{nombreArchivo}', [ProyectoController::class, 'descargarArchivo'])->name('proyectos.archivos.descargar');
    Route::delete('/proyectos/{id}/archivos/{nombreArchivo}', [ProyectoController::class, 'eliminarArchivo'])->name('proyectos.archivos.eliminar');
});

Route::middleware(['auth'])->group(function () {
    
    // Rutas protegidas por el nuevo portero (Middleware)
    Route::middleware(\App\Http\Middleware\CheckEmpresaAccess::class)->group(function () {
        
        // --- RUTAS DE EMPRESAS ---
        Route::resource('empresas', EmpresaController::class);

        // --- RUTAS DE PROYECTOS ---
        Route::resource('proyectos', ProyectoController::class);
        Route::patch('/proyectos/{id}/archivos/toggle', [ProyectoController::class, 'toggleVisibilidad'])->name('proyectos.archivos.toggle');
        Route::post('/proyectos/{id}/archivos/programar', [ProyectoController::class, 'programarMasivo'])->name('proyectos.archivos.programar');
        Route::patch('/proyectos/{id}/archivos/cancelar-programacion', [ProyectoController::class, 'cancelarProgramacion'])->name('proyectos.archivos.cancelar');
        Route::post('/proyectos/{id}/archivos/programar-publicacion', [ProyectoController::class, 'programarPublicacion'])->name('proyectos.archivos.programar_publicacion');
        Route::patch('/proyectos/{id}/archivos/cancelar-publicacion', [ProyectoController::class, 'cancelarPublicacion'])->name('proyectos.archivos.cancelar_publicacion');
        
    });    

    // --- RUTAS DE USUARIOS ---
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{usuario}', [UsuarioController::class, 'show'])->name('usuarios.show');
    Route::patch('/usuarios/{usuario}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('usuarios/{usuario}', [UsuarioController::class, 'destroy'])
    ->name('usuarios.destroy'); // (Le he quitado el ->middleware('auth') extra porque ya está dentro de un grupo auth)
});

// ── Rutas solo para ADMIN ──
Route::middleware(['auth', 'admin'])->group(function () {
    // Chatbot (POST) - solo admin puede usar el asistente IA
    Route::post('/chatbot/consulta', [ChatbotController::class, 'consulta'])->name('chatbot.consulta');
});

// Incluir rutas de autenticación
require __DIR__.'/auth.php';