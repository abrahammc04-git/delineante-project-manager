<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivos_chat', function (Blueprint $table) {
            $table->id('id_archivo');
            
            $table->unsignedBigInteger('id_mensaje');  // FK al mensaje al que pertenece este archivo
            $table->foreign('id_mensaje')->references('id_mensaje')->on('mensajes')->onDelete('cascade');
            
            $table->string('nombre_original');
            $table->string('ruta_storage');
            $table->integer('tamano'); // En KB o Bytes
            $table->string('mime_type')->nullable(); // Ej: application/pdf, image/jpeg
            $table->timestamps();  // created_at hará de 'fecha_subida'
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos_chat');
    }
};
