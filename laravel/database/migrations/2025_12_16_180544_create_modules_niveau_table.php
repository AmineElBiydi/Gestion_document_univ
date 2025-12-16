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
        Schema::create('modules_niveau', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('module_id')->index('modules_niveau_module_id_foreign');
            $table->unsignedBigInteger('filiere_id')->index('modules_niveau_filiere_id_foreign');
            $table->unsignedBigInteger('niveau_id')->index('modules_niveau_niveau_id_foreign');
            $table->decimal('coefficient', 4)->nullable()->default(1);
            $table->boolean('est_obligatoire')->nullable()->default(true);
            $table->timestamps();

            $table->unique(['module_id', 'filiere_id', 'niveau_id'], 'modules_niveau_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules_niveau');
    }
};
