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
             $table->id();
            $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
            $table->enum('type_document', [
                'attestation_scolaire',
                'attestation_reussite',
                'releve_notes',
                'convention_stage'
            ]);
            $table->string('num_demande')->unique();
            $table->date('date_demande');
            $table->enum('status', ['en_attente', 'en_cours', 'validee', 'rejetee'])->default('en_attente');
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
