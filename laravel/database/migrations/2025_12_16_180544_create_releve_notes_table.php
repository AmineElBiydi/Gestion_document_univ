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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demande_id')->index('releve_notes_demande_id_foreign');
            $table->unsignedBigInteger('decision_annee_id')->index('releve_notes_decision_annee_id_foreign');
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
