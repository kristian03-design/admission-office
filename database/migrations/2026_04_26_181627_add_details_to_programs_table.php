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
        Schema::table('programs', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->json('career_opportunities')->nullable()->after('duration_years');
            $table->json('core_areas')->nullable()->after('career_opportunities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['description', 'career_opportunities', 'core_areas']);
        });
    }
};
