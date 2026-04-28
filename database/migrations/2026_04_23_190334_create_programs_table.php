<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('department')->nullable();
            $table->string('category')->nullable(); // technology, business, education, hospitality, accountancy, arts&sciences
            $table->integer('duration_years')->default(4);
            $table->string('schedule')->nullable(); // Day, Evening, Day/Evening
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
