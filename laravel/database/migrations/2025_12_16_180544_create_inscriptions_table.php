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
        Schema::create('inscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('etudiant_id')->index('inscriptions_etudiant_id_foreign');
            $table->unsignedBigInteger('annee_id')->index('inscriptions_annee_id_foreign');
            $table->unsignedBigInteger('filiere_id')->index('inscriptions_filiere_id_foreign');
            $table->unsignedBigInteger('niveau_id')->index('inscriptions_niveau_id_foreign');
            $table->date('date_inscription');
            $table->enum('statut', ['inscrit', 'redoublant', 'diplome', 'abandonne'])->default('inscrit')->index('idx_inscriptions_statut');
            $table->timestamps();

            $table->unique(['etudiant_id', 'annee_id', 'filiere_id', 'niveau_id'], 'inscriptions_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscriptions');
    }
};
