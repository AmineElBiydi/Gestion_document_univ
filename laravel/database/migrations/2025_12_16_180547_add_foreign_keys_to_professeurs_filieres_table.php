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
        Schema::table('professeurs_filieres', function (Blueprint $table) {
            $table->foreign(['filiere_id'])->references(['id'])->on('filieres')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['professeur_id'])->references(['id'])->on('professeurs')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('professeurs_filieres', function (Blueprint $table) {
            $table->dropForeign('professeurs_filieres_filiere_id_foreign');
            $table->dropForeign('professeurs_filieres_professeur_id_foreign');
        });
    }
};
