<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '_functions.php');

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');
header('Content-Type: application/json');

$xml = $signal();
if (mb_strpos($xml, '<error>') !== false) {
    $login();
    $xml = $signal();
};
echo json_encode([
    'cell_id' => $parse_xml_value($xml, 'cell_id'),
    'rsrq' => $parse_xml_value($xml, 'rsrq'),
    'rsrp' => $parse_xml_value($xml, 'rsrp'),
    'sinr' => $parse_xml_value($xml, 'sinr'),
    'rssi' => $parse_xml_value($xml, 'rssi'),
]);
?>
