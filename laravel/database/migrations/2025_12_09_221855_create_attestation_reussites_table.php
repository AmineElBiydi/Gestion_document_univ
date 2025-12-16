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
        Schema::create('attestation_reussites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained()->onDelete('cascade');
            $table->string('filiere');
            $table->string('annee_universitaire');
            $table->string('cycle'); // Licence, Master, Doctorat
            $table->string('session'); // Normale, Rattrapage
            $table->string('type_releve'); // Normal, Provisoire, Définitif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestation_reussites');
    }
};
