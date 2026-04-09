<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('id_empresa'); // PK
            $table->string('nombre_empresa');
            $table->string('cif')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email_contacto')->nullable();
            $table->boolean('activo')->default(true);
            
            // Esto creará 'fecha_creacion(created_at)' y 'ultima_actualizacion(updated_at)' automáticamente
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }

};
