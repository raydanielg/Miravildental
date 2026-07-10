<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->text('link_url')->nullable()->after('gallery_paths');
            $table->string('link_title')->nullable()->after('link_url');
            $table->text('link_description')->nullable()->after('link_title');
            $table->text('link_image')->nullable()->after('link_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['link_url', 'link_title', 'link_description', 'link_image']);
        });
    }
};
