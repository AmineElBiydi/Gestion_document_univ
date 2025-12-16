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
        Schema::create('demandes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('etudiant_id')->index('demandes_etudiant_id_foreign');
            $table->unsignedBigInteger('inscription_id')->nullable()->index('demandes_inscription_id_foreign');
            $table->enum('type_document', ['attestation_scolaire', 'attestation_reussite', 'releve_notes', 'convention_stage'])->index('idx_demandes_type');
            $table->string('num_demande')->unique();
            $table->date('date_demande');
            $table->enum('status', ['en_attente', 'en_cours', 'validee', 'rejetee'])->default('en_attente')->index('idx_demandes_status');
            $table->text('raison_refus')->nullable();
            $table->timestamp('date_traitement')->nullable();
            $table->unsignedBigInteger('traite_par_admin_id')->nullable()->index('demandes_traite_par_admin_id_foreign');
            $table->string('fichier_genere_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
