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
        Schema::create('convention_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('demande_id')->index('convention_stages_demande_id_foreign');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('entreprise');
            $table->string('adresse_entreprise');
            $table->string('email_encadrant');
            $table->string('telephone_encadrant');
            $table->string('encadrant_entreprise');
            $table->unsignedBigInteger('encadrant_pedagogique_id')->nullable()->index('convention_stages_encadrant_pedagogique_id_foreign');
            $table->string('fonction_encadrant');
            $table->text('sujet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convention_stages');
    }
};
