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
        Schema::create('decisions_annee', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscription_id')->index('decisions_annee_inscription_id_foreign');
            $table->enum('type_session', ['normale', 'rattrapage'])->default('normale');
            $table->decimal('moyenne_annuelle', 5)->nullable();
            $table->integer('credits_valides')->nullable()->default(0);
            $table->integer('credits_totaux')->nullable()->default(0);
            $table->enum('mention', ['Passable', 'Assez Bien', 'Bien', 'Très Bien', 'Excellent'])->nullable();
            $table->enum('decision', ['admis', 'ajourne', 'redoublant', 'diplome']);
            $table->date('date_decision')->nullable();
            $table->timestamps();

            $table->unique(['inscription_id', 'type_session'], 'decisions_annee_inscription_session_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('decisions_annee');
    }
};
