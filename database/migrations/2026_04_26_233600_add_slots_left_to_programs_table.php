<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (!Schema::hasColumn('programs', 'slots_left')) {
                $table->unsignedSmallInteger('slots_left')->default(3000)->after('schedule');
            }
        });
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            if (Schema::hasColumn('programs', 'slots_left')) {
                $table->dropColumn('slots_left');
            }
        });
    }
};
