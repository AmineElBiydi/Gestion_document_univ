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
        Schema::table('attestation_scolaires', function (Blueprint $table) {
            $table->foreign(['demande_id'])->references(['id'])->on('demandes')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attestation_scolaires', function (Blueprint $table) {
            $table->dropForeign('attestation_scolaires_demande_id_foreign');
        });
    }
};
