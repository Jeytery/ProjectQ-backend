<?php

// Credentials

$username = getRequestValue('username');
$password = getRequestValue('password');

// Device Type | Number

$deviceType = getHeaderValue('DeviceType');
$deviceNumber = getHeaderValue('DeviceNumber');

// Timezone Offset

$timezoneOffset = getHeaderValue('TimezoneOffset');

if (!$timezoneOffset) {
    $timezoneOffset = getCookie('TimezoneOffset');
}

if (!$timezoneOffset) {
    $timezoneOffset = $database->getStringValue('SELECT value FROM settings WHERE name = "timezone_offset"');
}

if (!$timezoneOffset) {
    $timezoneOffset = "+03:00";
}

// Session timezone set up to client's timezone_offset
if ($timezoneOffset) {
    $database->query("SET @@session.time_zone = '$timezoneOffset'");
}

// Language Code

$languageCode = getRequestValue('language');

if (!$languageCode) {
    $languageCode = getHeaderValue('LanguageCode');
}

if (!$languageCode) {
    $languageCode = getCookie('LanguageCode');
}

//$mapProvider = getRequestValue('map');

?>
