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
        Schema::create('reclamations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demande_id')->nullable()->index('reclamations_demande_id_foreign');
            $table->unsignedBigInteger('etudiant_id')->index('reclamations_etudiant_id_foreign');
            $table->enum('type', ['retard', 'refus_injustifie', 'document_incorrect', 'probleme_technique', 'autre']);
            $table->text('description');
            $table->enum('status', ['non_traitee', 'en_cours', 'traitee', 'cloturee'])->default('non_traitee')->index('idx_reclamations_status');
            $table->string('piece_jointe_path')->nullable();
            $table->text('reponse')->nullable();
            $table->unsignedBigInteger('traite_par_admin_id')->nullable()->index('reclamations_traite_par_admin_id_foreign');
            $table->timestamp('date_traitement')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamations');
    }
};
