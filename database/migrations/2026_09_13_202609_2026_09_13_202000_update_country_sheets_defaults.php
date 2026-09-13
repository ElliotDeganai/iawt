<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE country_sheets ALTER COLUMN is_published SET DEFAULT 0");

        if (!Schema::hasColumn('country_sheets', 'flag_code')) {
            Schema::table('country_sheets', function (Blueprint $table) {
                $table->string('flag_code', 5)->nullable()->after('flag_emoji');
            });
        }
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE country_sheets ALTER COLUMN is_published SET DEFAULT 1");
    }
};