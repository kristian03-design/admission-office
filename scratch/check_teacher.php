<?php
$url = 'https://cdn.jsdelivr.net/npm/web-elements-icons@0.1.3/iconsax-sprite.svg';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

if (preg_match_all('/id="(linear-[^"]*teacher[^"]*)"/', $response, $matches)) {
    echo "Found teacher IDs:\n";
    print_r($matches[1]);
} else {
    echo "No teacher IDs found.\n";
}
