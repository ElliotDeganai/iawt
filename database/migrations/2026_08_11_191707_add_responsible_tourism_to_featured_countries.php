<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->json('responsible_tourism')->nullable()->after('tourism_agencies');
        });
    }

    public function down(): void
    {
        Schema::table('featured_countries', function (Blueprint $table) {
            $table->dropColumn('responsible_tourism');
        });
    }
};
