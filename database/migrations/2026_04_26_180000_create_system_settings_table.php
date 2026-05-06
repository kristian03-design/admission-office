<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('system_settings')->insert([
            ['key' => 'school_year',              'value' => 'S.Y. 2025–2026', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'application_deadline',     'value' => '2026-04-17',     'created_at' => now(), 'updated_at' => now()],
            ['key' => 'interview_schedule_text',  'value' => 'Monday – Friday, 9:00 AM – 3:00 PM', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'accept_applications',      'value' => '1',              'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email_notifications',      'value' => '1',              'created_at' => now(), 'updated_at' => now()],
            ['key' => 'scholarship_applications', 'value' => '1',              'created_at' => now(), 'updated_at' => now()],
            ['key' => 'institution_name',         'value' => 'Baliwag Polytechnic College', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_nav_label',           'value' => 'BTECH ADMISSION OFFICE', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'admissions_email',         'value' => 'admission@btech.edu.ph', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'campus_address',           'value' => 'Baliwag City, Bulacan 3006', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'contact_address',          'value' => 'Baliwag City, Bulacan 3006', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
