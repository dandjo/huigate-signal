<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php');

$login = function() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, CONFIG_ROUTER_HTTP_ROOT . '/html/home.html');
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, CONFIG_COOKIE_FILE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    ob_start(); // prevent any output
    curl_exec ($ch);
    ob_end_clean();
    if (curl_error($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
};

$signal = function() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, CONFIG_ROUTER_HTTP_ROOT . '/api/device/signal');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIEFILE, CONFIG_COOKIE_FILE);
    $xml = htmlspecialchars_decode(str_replace(array("\n", "\r"), '', curl_exec($ch)), ENT_XML1);
    if (curl_error($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $xml;
};

$parse_xml_value = function($xml, $tag_name) {
    $begin = "<$tag_name>";
    $end = "</$tag_name>";
    $begin_pos = mb_strpos($xml, $begin);
    $end_pos = mb_strpos($xml, $end);
    $begin_len = mb_strlen($begin);
    $end_len = mb_strlen($end);
    return mb_substr(
        $xml,
        $begin_pos + mb_strlen($begin),
        $end_pos - $begin_pos - mb_strlen($end) + 1
    );
};
?>
