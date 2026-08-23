<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 7)->default('#7A1F2B');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('event_category_id')->nullable()->after('id')->constrained('event_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['event_category_id']);
            $table->dropColumn('event_category_id');
        });
        Schema::dropIfExists('event_categories');
    }
};
