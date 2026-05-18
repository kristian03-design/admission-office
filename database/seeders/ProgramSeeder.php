<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            ['code' => 'BSIT', 'name' => 'Bachelor of Science in Information Technology', 'department' => 'Technology', 'category' => 'technology', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BA-MM', 'name' => 'BS Business Administration Major in Marketing Management', 'department' => 'Business', 'category' => 'business', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BA-FM', 'name' => 'BS Business Administration Major in Financial Management', 'department' => 'Business', 'category' => 'business', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BA-HRM', 'name' => 'BS Business Administration Major in Human Resource Management', 'department' => 'Business', 'category' => 'business', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BAECO', 'name' => 'BS Entrepreneurship', 'department' => 'Business', 'category' => 'business', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BSECON', 'name' => 'BS Economics', 'department' => 'Business', 'category' => 'business', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BSIA', 'name' => 'BS Internal Auditing', 'department' => 'Business', 'category' => 'business', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BSAIS', 'name' => 'BS Accounting Information System', 'department' => 'Business', 'category' => 'business', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BSA', 'name' => 'BS Accountancy', 'department' => 'Accountancy', 'category' => 'accountancy', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => true],
            ['code' => 'BSMA', 'name' => 'BS Management Accounting', 'department' => 'Accountancy', 'category' => 'accountancy', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'ABHISTORY', 'name' => 'Bachelor of Arts in History', 'department' => 'Arts & Sciences', 'category' => 'arts&sciences', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BSMATH', 'name' => 'Bachelor of Science in Mathematics', 'department' => 'Arts & Sciences', 'category' => 'arts&sciences', 'duration_years' => 4, 'schedule' => 'Day / Evening', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BEED', 'name' => 'Bachelor of Elementary Education', 'department' => 'Education', 'category' => 'education', 'duration_years' => 4, 'schedule' => 'Day', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => true],
            ['code' => 'BSED-ENG', 'name' => 'Bachelor of Secondary Education Major in English', 'department' => 'Education', 'category' => 'education', 'duration_years' => 4, 'schedule' => 'Day', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => true],
            ['code' => 'BSHM', 'name' => 'BS Hospitality Management', 'department' => 'Hospitality', 'category' => 'hospitality', 'duration_years' => 4, 'schedule' => 'Day', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
            ['code' => 'BSTM', 'name' => 'BS Tourism Management', 'department' => 'Hospitality', 'category' => 'hospitality', 'duration_years' => 4, 'schedule' => 'Day', 'slots_left' => 1500, 'is_active' => true, 'has_board_exam' => false],
        ];

        foreach ($programs as $program) {
            Program::updateOrCreate(
                ['code' => $program['code']],
                $program
            );
        }
    }
}
