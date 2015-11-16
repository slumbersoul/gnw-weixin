<?php //合理获取客户端IP地址;
if (isset($_SERVER["REMOTE_ADDR"]) &&  0!==strpos($_SERVER['REMOTE_ADDR'],'192.168') )
    $ip = $_SERVER["REMOTE_ADDR"];
else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
else if (isset($_SERVER["HTTP_CLIENT_IP"]))
    $ip = $_SERVER["HTTP_CLIENT_IP"];
else if (getenv("HTTP_X_FORWARDED_FOR"))
    $ip = getenv("HTTP_X_FORWARDED_FOR");
else if (getenv("HTTP_CLIENT_IP"))
    $ip = getenv("HTTP_CLIENT_IP");
else if (getenv("REMOTE_ADDR"))
    $ip = getenv("REMOTE_ADDR");
else
    $ip = false;

return $ip;
