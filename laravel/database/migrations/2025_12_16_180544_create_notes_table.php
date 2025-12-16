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
        Schema::create('notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscription_id')->index('notes_inscription_id_foreign');
            $table->unsignedBigInteger('module_niveau_id')->index('notes_module_niveau_id_foreign');
            $table->enum('type_session', ['normale', 'rattrapage'])->default('normale');
            $table->decimal('note', 5)->nullable();
            $table->boolean('est_valide')->nullable()->default(false);
            $table->timestamp('date_saisie')->nullable()->useCurrent();
            $table->timestamps();

            $table->unique(['inscription_id', 'module_niveau_id', 'type_session'], 'notes_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
