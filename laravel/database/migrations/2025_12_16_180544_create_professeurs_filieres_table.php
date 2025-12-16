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
        Schema::create('professeurs_filieres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('professeur_id')->index('professeurs_filieres_professeur_id_foreign');
            $table->unsignedBigInteger('filiere_id')->index('professeurs_filieres_filiere_id_foreign');
            $table->enum('role', ['responsable', 'coordinateur', 'enseignant'])->nullable()->default('enseignant');
            $table->timestamps();

            $table->unique(['professeur_id', 'filiere_id'], 'professeurs_filieres_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professeurs_filieres');
    }
};
