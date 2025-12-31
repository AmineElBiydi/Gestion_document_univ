<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('convention_stages', function (Blueprint $table) {
            if (!Schema::hasColumn('convention_stages', 'telephone_entreprise')) {
                $table->string('telephone_entreprise')->nullable()->after('secteur_entreprise');
            }
            if (!Schema::hasColumn('convention_stages', 'email_entreprise')) {
                $table->string('email_entreprise')->nullable()->after('telephone_entreprise');
            }
        });
    }

    public function down(): void
    {
        Schema::table('convention_stages', function (Blueprint $table) {
            $table->dropColumn(['telephone_entreprise', 'email_entreprise']);
        });
    }
};
