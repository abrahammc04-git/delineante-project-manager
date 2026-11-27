<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('proyectos', function (Blueprint $table) {
            if (!Schema::hasColumn('proyectos', 'id_empresa')) {
                $table->unsignedBigInteger('id_empresa')->nullable()->after('id_proyecto');
                $table->foreign('id_empresa')
                      ->references('id_empresa')
                      ->on('empresas')
                      ->onDelete('cascade');
            }
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
