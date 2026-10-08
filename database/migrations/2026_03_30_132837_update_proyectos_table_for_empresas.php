<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            // Añadimos la relación con empresas
            $table->unsignedBigInteger('id_empresa')->nullable()->after('id_proyecto');
            
            // Creamos la llave foránea
            $table->foreign('id_empresa')
                  ->references('id_empresa')
                  ->on('empresas')
                  ->onDelete('cascade'); // Si borras la empresa, se borran sus proyectos
        });
    }

    public function down(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->dropForeign(['id_empresa']);
            $table->dropColumn('id_empresa');
        });
    }

};
