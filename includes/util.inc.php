<?php
function get_navigateur(): string
{
    $agent = $_SERVER['HTTP_USER_AGENT'];

    if (str_contains($agent, 'Chrome')) {
        return "Google Chrome";
    } elseif (str_contains($agent, 'Firefox')) {
        return "Mozilla Firefox";
    } elseif (str_contains($agent, 'Safari')) {
        return "Safari";
    } elseif (str_contains($agent, 'Opera')) {
        return "Opera";
    } else {
        return $agent;
    }
}
