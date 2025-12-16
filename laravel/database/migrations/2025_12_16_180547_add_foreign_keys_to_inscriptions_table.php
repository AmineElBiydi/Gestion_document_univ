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
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->foreign(['annee_id'])->references(['id'])->on('annees_universitaires')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['etudiant_id'])->references(['id'])->on('etudiants')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['filiere_id'])->references(['id'])->on('filieres')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['niveau_id'])->references(['id'])->on('niveaux')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropForeign('inscriptions_annee_id_foreign');
            $table->dropForeign('inscriptions_etudiant_id_foreign');
            $table->dropForeign('inscriptions_filiere_id_foreign');
            $table->dropForeign('inscriptions_niveau_id_foreign');
        });
    }
};
