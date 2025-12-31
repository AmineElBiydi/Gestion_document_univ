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
        Schema::table('convention_stages', function (Blueprint $table) {
            $table->string('secteur_entreprise')->nullable()->after('entreprise');
            $table->string('telephone_entreprise')->nullable()->after('secteur_entreprise');
            $table->string('email_entreprise')->nullable()->after('telephone_entreprise');
            $table->string('ville_entreprise')->nullable()->after('adresse_entreprise');
            $table->string('representant_entreprise')->nullable()->after('ville_entreprise');
            $table->string('fonction_representant')->nullable()->after('representant_entreprise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convention_stages', function (Blueprint $table) {
            $table->dropColumn([
                'secteur_entreprise',
                'telephone_entreprise',
                'email_entreprise',
                'ville_entreprise',
                'representant_entreprise',
                'fonction_representant'
            ]);
        });
    }
};
