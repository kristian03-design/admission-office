<?php
$content = file_get_contents('public/js/admin-dashboard.js');
foreach(explode("\n", $content) as $i => $line) {
  if (stripos($line, 'Ongoing') !== false || stripos($line, 'Completed') !== false || stripos($line, 'STATUS') !== false || stripos($line, 'renderSchedules') !== false) {
    echo ($i+1) . ': ' . trim($line) . "\n";
  }
}
