<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            // Carte du pays
            $table->string('map_image')->nullable()->after('cover_image');

            // Bon à savoir
            $table->string('population')->nullable()->after('map_image');
            $table->string('currency')->nullable()->after('population');
            $table->string('timezone')->nullable()->after('currency');
            $table->string('official_language')->nullable()->after('timezone');
            $table->string('sunrise_time')->nullable()->after('official_language');
            $table->string('sunset_time')->nullable()->after('sunrise_time');
            $table->string('best_transport')->nullable()->after('sunset_time');

            // Listes flexibles (JSON)
            $table->json('highlights')->nullable()->after('best_transport'); // 12 sites incontournables
            $table->json('tourism_agencies')->nullable()->after('highlights'); // sociétés de tourisme locales
            $table->json('places')->nullable()->after('tourism_agencies'); // où se loger / shopping / manger / s'enjailler / se dépenser / apprendre
        });
    }

    public function down(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->dropColumn([
                'map_image', 'population', 'currency', 'timezone', 'official_language',
                'sunrise_time', 'sunset_time', 'best_transport',
                'highlights', 'tourism_agencies', 'places',
            ]);
        });
    }
};
