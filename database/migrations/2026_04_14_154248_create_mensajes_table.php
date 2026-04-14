<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id('id_mensaje');
            
            $table->unsignedBigInteger('id_conversacion');// FK a la conversación
            $table->foreign('id_conversacion')->references('id_conversacion')->on('conversaciones')->onDelete('cascade');
            
            $table->unsignedBigInteger('id_remitente');  // FK al remitente
            $table->foreign('id_remitente')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            
            $table->text('contenido')->nullable();
            $table->enum('tipo', ['texto', 'archivo'])->default('texto');
            $table->boolean('leido')->default(false);
            $table->timestamps(); // created_at hará de 'fecha_envio'
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
