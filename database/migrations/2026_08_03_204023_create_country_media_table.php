<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('country_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('featured_country_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['photo', 'video'])->default('photo');
            $table->string('path')->nullable();        // stockage local (photos)
            $table->string('video_url')->nullable();   // URL YouTube / Vimeo (vidéos)
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });

        // Lien cover vers un item de la galerie (nullable : cover directe toujours possible)
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->foreignId('cover_media_id')
                ->nullable()
                ->after('cover_image')
                ->constrained('country_media')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cover_media_id');
        });
        Schema::dropIfExists('country_media');
    }
};
