<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_channels', function (Blueprint $table) {
            $table->enum('topic_creation', ['everyone', 'admin_only'])->default('everyone')->after('moderation_mode');
        });
    }
    public function down(): void
    {
        Schema::table('forum_channels', function (Blueprint $table) { $table->dropColumn('topic_creation'); });
    }
};
