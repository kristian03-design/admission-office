<?php

$file = __DIR__ . '/../app/Http/Controllers/Api/NewsEventController.php';
$content = file_get_contents($file);

// Find and replace the broken line
$broken = "        \$uploadedUrls = [];\\n        foreach (\$files as \$file) {\\n            \$path = \$file->store('news-events', 'supabase');\\n            \$uploadedUrls[] = Storage::disk('supabase')->url(\$path);\\n        }";

$fixed = <<<'PHP'
        $uploadedUrls = [];
        foreach ($files as $file) {
            $path = $file->store('news-events', 'supabase');
            $uploadedUrls[] = Storage::disk('supabase')->url($path);
        }
PHP;

$result = str_replace($broken, $fixed, $content, $count);

if ($count > 0) {
    file_put_contents($file, $result);
    echo "Fixed $count occurrence(s).\n";
} else {
    echo "Pattern not found. Dumping line 173 area:\n";
    $lines = explode("\n", $content);
    for ($i = 170; $i <= 176; $i++) {
        echo "$i: " . var_export($lines[$i] ?? '', true) . "\n";
    }
}
