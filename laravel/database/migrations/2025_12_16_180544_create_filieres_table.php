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
        Schema::create('filieres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code_filiere', 20)->unique('filieres_code_unique');
            $table->string('nom_filiere');
            $table->enum('cycle', ['CP', 'CI', 'Licence', 'Master', 'Doctorat'])->index('idx_filieres_cycle');
            $table->text('description')->nullable();
            $table->boolean('est_active')->nullable()->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filieres');
    }
};
