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
        Schema::create('annees_universitaires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('libelle', 50)->unique();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('est_active')->nullable()->default(false)->index('idx_annees_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annees_universitaires');
    }
};
