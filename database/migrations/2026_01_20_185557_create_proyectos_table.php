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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id('id_proyecto');
            $table->unsignedBigInteger('id_usuario');
            $table->string('nombre_proyecto', 200);
            $table->text('descripcion')->nullable();
            $table->string('tipo_proyecto', 100)->nullable();
            $table->enum('estado', [
                'Pendiente',
                'En proceso',
                'En revisión',
                'Completado',
                'Pausado',
                'Cancelado'
            ])->default('Pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin_prevista')->nullable();
            $table->date('fecha_fin_real')->nullable();
            $table->string('localizacion', 150)->nullable();
            $table->text('direccion')->nullable();
            $table->string('carpeta_archivos', 255)->nullable();
            $table->text('notas')->nullable();
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('ultima_actualizacion')->useCurrent()->useCurrentOnUpdate();
            
            // Relación con usuarios
            $table->foreign('id_usuario')
                ->references('id_usuario')
                ->on('usuarios')
                ->onDelete('cascade');
            
            // Índices
            $table->index('id_usuario');
            $table->index('estado');
            $table->index('tipo_proyecto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};