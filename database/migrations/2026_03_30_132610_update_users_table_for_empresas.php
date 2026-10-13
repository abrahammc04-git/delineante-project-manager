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

            if (!Schema::hasColumn('usuarios', 'id_empresa')) {
                $table->unsignedBigInteger('id_empresa')->nullable()->after('id_usuario');
                $table->foreign('id_empresa')
                      ->references('id_empresa')
                      ->on('empresas')
                      ->onDelete('set null');
            }
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
