<?php
// config/allowed_ips.php

$ips = trim(env('ALLOWED_IPS', ''));
$allowedIps = [];

// Only parse the IP addresses if the value is not empty
if (!empty($ips)) {
    $allowedIps = array_map('trim', explode(',', $ips));
}

return [
    'ips' => $allowedIps,
];
