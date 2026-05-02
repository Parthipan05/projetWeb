<?php
function get_navigateur(): string
{
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $agent = $_SERVER['HTTP_USER_AGENT'];
    } else {
        $agent = 'inconnu';
    }

    if (strpos($agent, 'Chrome') !== false) {
        return "Google Chrome";
    } elseif (strpos($agent, 'Firefox') !== false) {
        return "Mozilla Firefox";
    } elseif (strpos($agent, 'Safari') !== false) {
        return "Safari";
    } elseif (strpos($agent, 'Opera') !== false) {
        return "Opera";
    } else {
        return htmlspecialchars($agent);
    }
}
