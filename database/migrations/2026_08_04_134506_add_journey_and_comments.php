<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Parcours : étape la plus haute débloquée (0 = aucune)
        Schema::table('applications', function (Blueprint $table) {
            $table->unsignedTinyInteger('journey_current_step')->default(0)->after('status');
            $table->text('rejection_reason')->nullable()->after('journey_current_step');
        });

        // Commentaires admin sur une candidature
        Schema::create('admin_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // l'admin qui commente
            $table->text('body');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_comments');
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['journey_current_step', 'rejection_reason']);
        });
    }
};
