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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demande_id')->index('attestation_reussites_demande_id_foreign');
            $table->unsignedBigInteger('decision_annee_id')->index('attestation_reussites_decision_annee_id_foreign');
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
