<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journey_responses', function (Blueprint $table) {
            $table->timestamp('validated_at')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('journey_responses', function (Blueprint $table) {
            $table->dropColumn('validated_at');
        });
    }
};