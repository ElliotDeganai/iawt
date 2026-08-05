<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            // Code ISO 3166-1 alpha-2 (ex: 'ci', 'sn') pointant vers une icône du paquet flag-icons.
            // Prioritaire sur flag_emoji/flag_image quand renseigné.
            $table->string('flag_code', 2)->nullable()->after('flag_image');
        });
    }

    public function down(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->dropColumn('flag_code');
        });
    }
};