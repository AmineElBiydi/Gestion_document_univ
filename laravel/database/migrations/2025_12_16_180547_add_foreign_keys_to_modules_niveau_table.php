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
        Schema::table('modules_niveau', function (Blueprint $table) {
            $table->foreign(['filiere_id'])->references(['id'])->on('filieres')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['module_id'])->references(['id'])->on('modules')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['niveau_id'])->references(['id'])->on('niveaux')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules_niveau', function (Blueprint $table) {
            $table->dropForeign('modules_niveau_filiere_id_foreign');
            $table->dropForeign('modules_niveau_module_id_foreign');
            $table->dropForeign('modules_niveau_niveau_id_foreign');
        });
    }
};
