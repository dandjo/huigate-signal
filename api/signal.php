<?php
error_reporting(E_ALL);

// login
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://homerouter.cpe/html/index.html');
curl_setopt($ch, CURLOPT_COOKIESESSION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookie.txt');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
ob_start(); // prevent any output
curl_exec ($ch);
ob_end_clean();
if (curl_error($ch)) {
    header('Content-Type: text/plain');
    echo curl_error($ch);
    exit;
}
curl_close($ch);
unset($ch);

// signal
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://homerouter.cpe/api/device/signal');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");
$data = curl_exec($ch);
if (curl_error($ch)) {
    header('Content-Type: text/plain');
    echo curl_error($ch);
    exit;
}
curl_close($ch);

// output
header('Content-Type: application/json');
echo json_encode(simplexml_load_string($data));
?>
