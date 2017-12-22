<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type: application/json');

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
$xml = str_replace(array("\n", "\r"), '', curl_exec($ch));
if (curl_error($ch)) {
    header('Content-Type: text/plain');
    echo curl_error($ch);
    exit;
}
curl_close($ch);

// output
$parse_xml_value = function($xml, $key) {
    $begin = "<$key>";
    $end = "</$key>";
    $begin_pos = mb_strpos($xml, $begin);
    $end_pos = mb_strpos($xml, $end);
    $begin_len = mb_strlen($begin);
    $end_len = mb_strlen($end);
    return htmlspecialchars_decode(mb_substr(
        $xml,
        $begin_pos + mb_strlen($begin),
        $end_pos - $begin_pos - mb_strlen($end) + 1
    ), ENT_XML1);
};
echo json_encode([
    'cell_id' => $parse_xml_value($xml, 'cell_id'),
    'rsrq' => $parse_xml_value($xml, 'rsrq'),
    'rsrp' => $parse_xml_value($xml, 'rsrp'),
    'sinr' => $parse_xml_value($xml, 'sinr'),
    'rssi' => $parse_xml_value($xml, 'rssi'),
]);
?>
