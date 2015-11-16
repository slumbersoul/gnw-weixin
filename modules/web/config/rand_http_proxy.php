<?php //function rand_http_proxy($always_rand=false){
/** 
 * 随机出现一个http代理
 *   默认 $always_rand = false, 即单个请求内只生成唯一一个http地址;
 *   如果想要不同的地址, 设置入参为 rand_http_proxy(true);
 */
//@param boolean $always_rand
global $RAND_HTTP_PROXY;
if(false==$always_rand && !empty($RAND_HTTP_PROXY) )    
    return $RAND_HTTP_PROXY;

$proxy = array(
    'http://192.168.2.2:8181', 
    'http://192.168.2.4:8181',
    'http://192.168.2.6:8181',
    'http://192.168.2.8:8181',
    'http://192.168.2.16:8181',
    'http://192.168.2.18:8181',
    'http://192.168.2.20:8181',
    'http://192.168.2.22:8181',
    'http://192.168.2.26:8181',
    'http://192.168.2.28:8181',
    'http://192.168.2.30:8181',
);

$k = array_rand($proxy);
$RAND_HTTP_PROXY = $proxy[$k]; 
return $RAND_HTTP_PROXY;
