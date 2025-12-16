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
        Schema::table('notes', function (Blueprint $table) {
            $table->foreign(['inscription_id'])->references(['id'])->on('inscriptions')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['module_niveau_id'])->references(['id'])->on('modules_niveau')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropForeign('notes_inscription_id_foreign');
            $table->dropForeign('notes_module_niveau_id_foreign');
        });
    }
};
