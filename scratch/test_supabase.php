<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;

echo "=== Supabase Storage Final Test ===\n\n";
echo "Bucket: " . config('filesystems.disks.supabase.bucket') . "\n\n";

try {
    $testPath = 'test/connection-test.txt';
    $result = Storage::disk('supabase')->put($testPath, 'Supabase connection test from Laravel - ' . date('Y-m-d H:i:s'));
    
    if ($result) {
        $url = Storage::disk('supabase')->url($testPath);
        echo "✅ Upload SUCCESS!\n";
        echo "Public URL: $url\n\n";
        Storage::disk('supabase')->delete($testPath);
        echo "✅ Delete SUCCESS!\n";
        echo "\nSupabase Storage is fully configured and working!\n";
    } else {
        echo "❌ Upload failed (returned false)\n";
    }
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
