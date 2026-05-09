<?php
$url = 'https://cdn.jsdelivr.net/npm/web-elements-icons@0.1.3/iconsax-sprite.svg';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($httpCode == 200) {
    echo "First 500 chars:\n";
    echo substr($response, 0, 500) . "\n";
    echo "Check for 'linear-teacher': " . (strpos($response, 'linear-teacher') !== false ? "FOUND" : "NOT FOUND") . "\n";
} else {
    echo "Failed to fetch sprite.\n";
}
