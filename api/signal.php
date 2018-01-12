<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php');
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '_helpers.php');

function signal() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, CONFIG_ROUTER_HTTP_ROOT . '/api/device/signal');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, CONFIG_COOKIE_FILE);
    ob_start(); // prevent any output
    $xml = str_replace(["\n", "\r"], '', curl_exec($ch));
    ob_end_clean();
    if (curl_error($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $xml;
}

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type: application/json');

$xml = signal();
if (mb_strpos($xml, '<error>') !== false) {
    parse_csrf_token();
    $xml = signal();
};
echo json_encode([
    'cell_id' => parse_xml_value($xml, 'cell_id'),
    'rsrq' => parse_xml_value($xml, 'rsrq'),
    'rsrp' => parse_xml_value($xml, 'rsrp'),
    'sinr' => parse_xml_value($xml, 'sinr'),
    'rssi' => parse_xml_value($xml, 'rssi'),
]);
?>
