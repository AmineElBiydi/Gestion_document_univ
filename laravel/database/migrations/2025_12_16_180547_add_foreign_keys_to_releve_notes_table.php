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
        Schema::table('releve_notes', function (Blueprint $table) {
            $table->foreign(['decision_annee_id'])->references(['id'])->on('decisions_annee')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['demande_id'])->references(['id'])->on('demandes')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('releve_notes', function (Blueprint $table) {
            $table->dropForeign('releve_notes_decision_annee_id_foreign');
            $table->dropForeign('releve_notes_demande_id_foreign');
        });
    }
};
