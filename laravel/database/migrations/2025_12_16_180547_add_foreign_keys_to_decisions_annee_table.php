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
        Schema::table('decisions_annee', function (Blueprint $table) {
            $table->foreign(['inscription_id'])->references(['id'])->on('inscriptions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decisions_annee', function (Blueprint $table) {
            $table->dropForeign('decisions_annee_inscription_id_foreign');
        });
    }
};
