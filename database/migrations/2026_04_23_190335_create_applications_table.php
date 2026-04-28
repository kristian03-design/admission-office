<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique()->nullable();

            // Step 1 – Program & Type
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->string('applicant_type')->nullable(); // freshman, transferee, als_graduate, returnee

            // Step 2 – Personal Info
            $table->string('last_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('sex')->nullable();
            $table->string('civil_status')->nullable();
            $table->string('religion')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('email')->nullable();
            $table->string('contact_number')->nullable();

            // Step 3 – Address
            $table->text('permanent_address')->nullable();
            $table->text('present_address')->nullable();

            // Step 4 – Family Background
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_contact')->nullable();

            // Step 5 – Educational Background
            $table->string('elementary_school')->nullable();
            $table->string('elementary_year_graduated')->nullable();
            $table->string('junior_high_school')->nullable();
            $table->string('junior_high_year_graduated')->nullable();
            $table->string('senior_high_school')->nullable();
            $table->string('senior_high_strand')->nullable();
            $table->string('senior_high_year_graduated')->nullable();
            $table->string('previous_college')->nullable();
            $table->string('previous_college_program')->nullable();
            $table->string('previous_college_year_last_attended')->nullable();

            // Steps 6–10 – Additional fields (scholarship, health, emergency, etc.)
            $table->string('scholarship_type')->nullable();
            $table->string('scholarship_name')->nullable();
            $table->text('health_conditions')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_number')->nullable();

            // Step 11–12 – Documents & Status
            $table->string('photo_path')->nullable();
            $table->string('birth_certificate_path')->nullable();
            $table->string('report_card_path')->nullable();
            $table->string('good_moral_path')->nullable();
            $table->string('tor_path')->nullable();
            $table->string('diploma_path')->nullable();

            $table->string('status')->default('pending'); // pending, under_review, approved, rejected
            $table->text('admin_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
