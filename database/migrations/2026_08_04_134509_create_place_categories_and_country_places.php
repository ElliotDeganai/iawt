<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('place_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');       // "Où manger", "Où se loger"
            $table->string('slug')->unique();
            $table->string('icon');       // slug d'icône (Icon.vue ou SVG path)
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        Schema::create('country_places', function (Blueprint $table) {
            $table->id();
            $table->foreignId('featured_country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('place_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_places');
        Schema::dropIfExists('place_categories');
    }
};
