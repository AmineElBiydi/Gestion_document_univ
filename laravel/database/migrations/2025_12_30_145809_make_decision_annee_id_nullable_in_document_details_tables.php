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
        Schema::table('attestation_reussites', function (Blueprint $table) {
            $table->unsignedBigInteger('decision_annee_id')->nullable()->change();
        });

        Schema::table('releve_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('decision_annee_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attestation_reussites', function (Blueprint $table) {
            $table->unsignedBigInteger('decision_annee_id')->nullable(false)->change();
        });

        Schema::table('releve_notes', function (Blueprint $table) {
            $table->unsignedBigInteger('decision_annee_id')->nullable(false)->change();
        });
    }
};
