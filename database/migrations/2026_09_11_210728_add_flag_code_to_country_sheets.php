<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('country_sheets', function (Blueprint $table) {
            $table->string('flag_code', 5)->nullable()->after('flag_emoji');
        });
    }

    public function down(): void
    {
        Schema::table('country_sheets', function (Blueprint $table) {
            $table->dropColumn('flag_code');
        });
    }
};