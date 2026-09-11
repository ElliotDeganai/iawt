<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('country_name')->unique();
            $table->string('flag_emoji', 10)->nullable();
            $table->string('title');
            $table->text('intro')->nullable();
            $table->text('warning')->nullable();
            $table->json('actors')->nullable();
            $table->string('organism_name')->nullable();
            $table->string('organism_full')->nullable();
            $table->text('organism_desc')->nullable();
            $table->string('organism_address')->nullable();
            $table->string('organism_hours')->nullable();
            $table->string('organism_website')->nullable();
            $table->json('documents')->nullable();
            $table->json('individual_steps')->nullable();
            $table->json('company_steps')->nullable();
            $table->text('fees')->nullable();
            $table->text('sector_auth')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_sheets');
    }
};
