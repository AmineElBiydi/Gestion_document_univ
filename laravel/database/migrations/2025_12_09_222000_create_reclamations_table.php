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
            $table->id();
            $table->foreignId('demande_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->enum('type', [
                'retard',
                'refus_injustifie', 
                'document_incorrect',
                'probleme_technique'
            ]);
            $table->text('description');
            $table->enum('status', ['non_traitee', 'en_cours', 'traitee'])->default('non_traitee');
            $table->string('piece_jointe_path')->nullable();
            $table->text('reponse')->nullable();
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
