<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_channels', function (Blueprint $table) {
            $table->enum('moderation_mode', ['inherit', 'strict', 'soft'])->default('inherit')->after('is_active');
        });
    }
    public function down(): void
    {
        Schema::table('forum_channels', function (Blueprint $table) { $table->dropColumn('moderation_mode'); });
    }
};
