<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('email', 150)->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('empresa', 150)->nullable();
            $table->string('password_hash');
            $table->enum('rol', ['cliente', 'admin'])->default('cliente');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('activo')->default(true);
            
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};