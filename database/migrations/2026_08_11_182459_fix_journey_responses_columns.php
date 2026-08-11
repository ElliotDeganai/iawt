<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journey_responses', function (Blueprint $table) {
            // Ajouter user_id si manquant
            if (!Schema::hasColumn('journey_responses', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            // Ajouter data si manquant
            if (!Schema::hasColumn('journey_responses', 'data')) {
                $table->json('data')->nullable()->after('step_number');
            }
        });

        // Copier les données de l'ancienne structure vers la nouvelle
        if (Schema::hasColumn('journey_responses', 'application_id')) {
            DB::statement('
                UPDATE journey_responses jr
                JOIN applications a ON a.id = jr.application_id
                SET jr.user_id = a.user_id
                WHERE jr.user_id IS NULL
            ');
        }

        if (Schema::hasColumn('journey_responses', 'responses')) {
            DB::statement('
                UPDATE journey_responses
                SET data = responses
                WHERE data IS NULL AND responses IS NOT NULL
            ');
        }

        // Supprimer les anciennes colonnes
        Schema::table('journey_responses', function (Blueprint $table) {
            if (Schema::hasColumn('journey_responses', 'application_id')) {
                $table->dropForeign(['application_id']);
                $table->dropColumn('application_id');
            }
            if (Schema::hasColumn('journey_responses', 'responses')) {
                $table->dropColumn('responses');
            }
            if (Schema::hasColumn('journey_responses', 'status')) {
                $table->dropColumn('status');
            }
        });

        // Rendre user_id non nullable et ajouter la contrainte
        Schema::table('journey_responses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->unique(['user_id', 'step_number']);
        });
    }

    public function down(): void
    {
        // Non réversible proprement
    }
};
