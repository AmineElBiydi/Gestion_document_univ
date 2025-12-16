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
        Schema::create('professeurs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('matricule', 50)->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone', 20)->nullable();
            $table->string('specialite')->nullable();
            $table->enum('grade', ['Professeur', 'Professeur Habilité', 'Professeur Associé', 'Professeur Assistant'])->nullable();
            $table->boolean('est_actif')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professeurs');
    }
};
