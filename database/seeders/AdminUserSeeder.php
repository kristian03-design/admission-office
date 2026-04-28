<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'btechadmissionoffice@gmail.com';

        $existing = User::where('email', $email)->first();

        if ($existing) {
            $this->command->info("Admin account already exists: {$email}");
            return;
        }

        User::create([
            'name'              => 'Admin',
            'email'             => $email,
            'password'          => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        $this->command->info("✅ Admin account created successfully!");
        $this->command->line("   Email:    {$email}");
        $this->command->line("   Password: admin123");
    }
}
