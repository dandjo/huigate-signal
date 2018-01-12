<?php
require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.php');

function parse_csrf_token() {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, CONFIG_ROUTER_HTTP_ROOT . '/html/home.html');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIESESSION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, CONFIG_COOKIE_FILE);
    ob_start(); // prevent any output
    $html = curl_exec($ch);
    ob_end_clean();
    if (curl_error($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    preg_match('/meta name=\"csrf_token\" content=\"(.*)\"/', $html, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    }
    return null;
}

function parse_xml_value($xml, $tag_name) {
    preg_match("/<$tag_name>(.*)<\/$tag_name>/", $xml, $matches);
    if (isset($matches[1])) {
        return htmlspecialchars_decode($matches[1]);
    }
    return null;
}
?>
