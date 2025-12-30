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
        Schema::create('demande_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_id')->constrained('demandes')->onDelete('cascade');
            // user_id can be nullable (system action) or refer to an admin/user
            // We'll store the ID of the user who performed the action. 
            // Since we have both Admins and Etudiants, maybe we should use a polymorphic relation or just nullable user_id + role?
            // For now, let's stick to nullable user_id and maybe 'actor_type' or just descriptive text in 'details'.
            // Simpler: 'traite_par_admin_id' logic is used in Demande, but History can act by Student too?
            // Let's keep it simple: user_id (nullable) + action + details.
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('action'); // e.g., 'created', 'validated', 'rejected', 'recurred'
            $table->text('details')->nullable(); // JSON or text, e.g. reason for refusal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_historiques');
    }
};
