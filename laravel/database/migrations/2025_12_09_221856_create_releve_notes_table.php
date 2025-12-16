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
        Schema::create('releve_notes', function (Blueprint $table) {
           $table->id();
            $table->foreignId('demande_id')->constrained()->onDelete('cascade');
            $table->string('semestre'); // S1, S2, S3, S4, S5, S6
            $table->string('annee_universitaire');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('releve_notes');
    }
};
