<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversaciones', function (Blueprint $table) {
            $table->id('id_conversacion');
            
            $table->unsignedBigInteger('id_usuario'); // FK al usuario destinatario (el cliente normal)
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            
            $table->string('titulo')->nullable(); // Por si el admin quiere poner "Envío de planos"
            $table->boolean('activo')->default(true);
            $table->timestamps(); // created_at hará de 'fecha_creacion'
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversaciones');
    }
};
