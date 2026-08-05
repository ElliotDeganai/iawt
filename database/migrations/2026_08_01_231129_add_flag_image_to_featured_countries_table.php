<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->string('flag_image')->nullable()->after('flag_emoji');
        });
    }

    public function down(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->dropColumn('flag_image');
        });
    }
};
