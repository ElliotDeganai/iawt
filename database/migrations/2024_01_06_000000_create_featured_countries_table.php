<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('flag_emoji', 10)->nullable();
            $table->string('tags')->nullable(); // ex: "Agriculture • Tourisme • Opportunités"
            $table->text('summary')->nullable(); // texte court utilisé sur la home
            $table->longText('content')->nullable(); // contenu complet de la page dédiée
            $table->string('cover_image')->nullable();
            $table->date('featured_month'); // 1er jour du mois mis à l'honneur
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_countries');
    }
};