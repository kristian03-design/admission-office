<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('gwa_grade_11')->nullable();
            $table->string('gwa_grade_12')->nullable();
            $table->string('first_choice')->nullable();
            $table->string('second_choice')->nullable();
            $table->string('pwd')->nullable();
            $table->string('solo_parent')->nullable();
            $table->string('four_ps')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('semester')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn([
                'gwa_grade_11', 'gwa_grade_12', 'first_choice', 'second_choice',
                'pwd', 'solo_parent', 'four_ps', 'academic_year', 'semester'
            ]);
        });
    }
};
