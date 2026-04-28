<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->index(['is_active', 'is_popup', 'sort_order', 'created_at'], 'announcements_public_listing_idx');
            $table->index(['starts_at', 'ends_at'], 'announcements_active_window_idx');
        });

        Schema::table('news_events', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order', 'event_date', 'created_at'], 'news_events_public_listing_idx');
            $table->index(['is_active', 'type'], 'news_events_active_type_idx');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->index('name', 'programs_public_listing_idx');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->index(['is_active', 'order'], 'testimonials_public_listing_idx');
        });
    }

    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropIndex('announcements_public_listing_idx');
            $table->dropIndex('announcements_active_window_idx');
        });

        Schema::table('news_events', function (Blueprint $table) {
            $table->dropIndex('news_events_public_listing_idx');
            $table->dropIndex('news_events_active_type_idx');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropIndex('programs_public_listing_idx');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropIndex('testimonials_public_listing_idx');
        });
    }
};
