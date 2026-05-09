<?php
$files = [
    __DIR__ . '/../app/Http/Controllers/Api/TestimonialController.php',
    __DIR__ . '/../app/Http/Controllers/Api/NewsEventController.php',
    __DIR__ . '/../app/Http/Controllers/Api/FacultyStaffController.php',
    __DIR__ . '/../app/Http/Controllers/Api/AnnouncementController.php',
];

foreach ($files as $path) {
    $c = file_get_contents($path);
    $c = str_replace("'admission-media'", "'file_image'", $c, $n);
    file_put_contents($path, $c);
    echo basename($path) . ": $n replacement(s)\n";
}

echo "\nDone!\n";
