<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'btechadmissionoffice@gmail.com');
        $password = env('ADMIN_INITIAL_PASSWORD') ?: Str::password(24);

        $existing = User::where('email', $email)->first();

        if ($existing) {
            $existing->forceFill(['is_admin' => true])->save();
            $this->command->info("Admin account already exists and is marked admin: {$email}");
            return;
        }

        User::create([
            'name' => 'Admin',
            'email' => $email,
            'is_admin' => true,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin account created successfully.');
        $this->command->line("   Email:    {$email}");
        $this->command->line("   Password: {$password}");
        $this->command->warn('Store this password safely, then remove ADMIN_INITIAL_PASSWORD from production env.');
    }
}
