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
        Schema::table('convention_stages', function (Blueprint $table) {
            $table->foreign(['demande_id'])->references(['id'])->on('demandes')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['encadrant_pedagogique_id'])->references(['id'])->on('professeurs')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convention_stages', function (Blueprint $table) {
            $table->dropForeign('convention_stages_demande_id_foreign');
            $table->dropForeign('convention_stages_encadrant_pedagogique_id_foreign');
        });
    }
};
