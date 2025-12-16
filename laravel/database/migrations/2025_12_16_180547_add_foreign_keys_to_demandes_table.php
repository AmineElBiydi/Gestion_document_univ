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
        Schema::table('demandes', function (Blueprint $table) {
            $table->foreign(['etudiant_id'])->references(['id'])->on('etudiants')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['inscription_id'])->references(['id'])->on('inscriptions')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['traite_par_admin_id'])->references(['id'])->on('admins')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropForeign('demandes_etudiant_id_foreign');
            $table->dropForeign('demandes_inscription_id_foreign');
            $table->dropForeign('demandes_traite_par_admin_id_foreign');
        });
    }
};
