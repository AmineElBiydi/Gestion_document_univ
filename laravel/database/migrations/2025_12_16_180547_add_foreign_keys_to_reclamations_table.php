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
        Schema::table('reclamations', function (Blueprint $table) {
            $table->foreign(['demande_id'])->references(['id'])->on('demandes')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['etudiant_id'])->references(['id'])->on('etudiants')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['traite_par_admin_id'])->references(['id'])->on('admins')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reclamations', function (Blueprint $table) {
            $table->dropForeign('reclamations_demande_id_foreign');
            $table->dropForeign('reclamations_etudiant_id_foreign');
            $table->dropForeign('reclamations_traite_par_admin_id_foreign');
        });
    }
};
