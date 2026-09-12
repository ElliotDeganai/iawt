<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'hidden'])->default('pending')->after('is_pinned');
        });
        // Auto-approve existing posts
        \DB::table('forum_posts')->update(['status' => 'approved']);
    }

    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
