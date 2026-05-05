<?php
$homePageCss = file_get_contents('c:/xampp/htdocs/admission-office/public/css/home-page.css');
$lines = explode("\n", $homePageCss);
$loaderCss = implode("\n", array_slice($lines, 60, 111));
file_put_contents('c:/xampp/htdocs/admission-office/public/css/custom.css', "\n/* Site Loader Styles */\n" . $loaderCss, FILE_APPEND);
echo "Appended successfully.";
