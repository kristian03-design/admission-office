<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Insert directly into database using Query Builder to bypass model mutators
$email = 'btechadmissionoffice@gmail.com';
$adminExists = DB::table('adminlist')->where('email', $email)->exists();

if ($adminExists) {
    echo "Admin account already exists.\n";
}
else {
    DB::table('adminlist')->insert([
        'full_name' => 'Admin',
        'email' => $email,
        'password' => Hash::make('admin123'),
        'position' => 'Admin',
        'status' => 'Active',
        'created_at' => now(),
        'updated_at' => now()
    ]);

    echo "Admin account created successfully!\n";
}

echo "Email: btechadmissionoffice@gmail.com\n";
echo "Password: admin123\n";

// Verify it
$admin = DB::table('adminlist')->first();
echo "\nVerification:\n";
echo "Email in DB: " . $admin->email . "\n";
echo "Password works: " . (Hash::check('admin123', $admin->password) ? 'YES' : 'NO') . "\n";
