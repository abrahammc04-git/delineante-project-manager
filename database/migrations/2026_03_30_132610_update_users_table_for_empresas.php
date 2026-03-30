<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Eliminamos la columna antigua
            if (Schema::hasColumn('usuarios', 'empresa')) {
                $table->dropColumn('empresa');
            }

            // Añadimos la relación con empresas (nullable al principio para que no falle con datos antiguos)
            $table->unsignedBigInteger('id_empresa')->nullable()->after('id_usuario');
            
            // Creamos la clave foránea
            $table->foreign('id_empresa')
                  ->references('id_empresa')
                  ->on('empresas')
                  ->onDelete('set null'); // Si se borra la empresa, el usuario no se borra, se queda nulo
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['id_empresa']);
            $table->dropColumn('id_empresa');
            $table->string('empresa')->nullable(); // Restauramos la antigua por si hacemos rollback
        });
    }

};
