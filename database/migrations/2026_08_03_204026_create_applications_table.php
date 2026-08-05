<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Progression du wizard (1-4, null = pas encore commencé)
            $table->unsignedTinyInteger('current_step')->default(1);
            $table->enum('status', ['draft', 'submitted', 'accepted', 'rejected'])->default('draft');

            // ── Étape 1 : Profil personnel ────────────────────────────────
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('country_of_residence')->nullable();
            $table->string('city_of_residence')->nullable();
            $table->string('postal_address')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('whatsapp_phone')->nullable();
            $table->boolean('has_internet_access')->nullable();
            $table->enum('current_status', ['student', 'employee', 'entrepreneur', 'unemployed'])->nullable();
            $table->string('university_name')->nullable();
            $table->string('university_doc')->nullable(); // fichier
            $table->decimal('weekly_hours', 4, 1)->nullable();

            // ── Étape 2 : Votre projet ────────────────────────────────────
            $table->string('project_name')->nullable();
            $table->text('project_problem')->nullable();    // max 50 mots
            $table->text('project_solution')->nullable();   // max 100 mots
            $table->string('project_sector')->nullable();
            $table->text('project_competition')->nullable(); // max 50 mots
            $table->string('project_pitch')->nullable();     // max 50 mots
            $table->enum('project_stage', ['wish', 'idea', 'business_model', 'funding', 'legal'])->nullable();
            $table->boolean('team_project')->nullable();

            // ── Étape 3 : Documents & liens ───────────────────────────────
            $table->string('cv_file')->nullable();
            $table->string('linkedin_url')->nullable();

            // ── Étape 4 : Compléments & consentements ─────────────────────
            $table->boolean('previous_program')->nullable();
            $table->json('support_needs')->nullable(); // tableau de choix
            $table->string('heard_about_us')->nullable();
            $table->boolean('consent_data')->default(false);
            $table->boolean('consent_image')->default(false);

            $table->timestamps();
            $table->timestamp('submitted_at')->nullable();

            $table->unique('user_id'); // une candidature par utilisateur
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
