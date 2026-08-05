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
        Schema::table('applications', function (Blueprint $table) {
            $table->text('project_pitch')->nullable()->change();
            $table->text('project_competition')->nullable()->change();
            $table->text('project_problem')->nullable()->change();
            $table->text('project_solution')->nullable()->change();
            $table->text('postal_address')->nullable()->change();
            $table->text('heard_about_us')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            //
        });
    }
};
