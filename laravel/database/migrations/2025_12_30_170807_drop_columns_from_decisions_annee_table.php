<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('decisions_annee', function (Blueprint $table) {
            // Drop the old unique constraint
            $table->dropUnique('decisions_annee_inscription_session_unique');

            // Drop the columns
            $table->dropColumn(['type_session', 'credits_valides', 'credits_totaux']);

            // Add new unique constraint on inscription_id only
            $table->unique('inscription_id', 'decisions_annee_inscription_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('decisions_annee', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique('decisions_annee_inscription_unique');

            // Restore the columns
            $table->enum('type_session', ['normale', 'rattrapage'])->default('normale')->after('inscription_id');
            $table->integer('credits_valides')->nullable()->default(0)->after('moyenne_annuelle');
            $table->integer('credits_totaux')->nullable()->default(0)->after('credits_valides');

            // Restore the old unique constraint
            $table->unique(['inscription_id', 'type_session'], 'decisions_annee_inscription_session_unique');
        });
    }
};
