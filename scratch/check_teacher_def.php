<?php
$url = 'https://cdn.jsdelivr.net/npm/web-elements-icons@0.1.3/iconsax-sprite.svg';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
curl_close($ch);

$pos = strpos($response, 'id="linear-teacher"');
if ($pos !== false) {
    echo "Found linear-teacher at $pos\n";
    echo substr($response, $pos - 50, 500) . "\n";
} else {
    echo "linear-teacher not found.\n";
}
