<?php 
$VAR_ZCACHE = array
(
    'default' => array
    (
        'driver'  => 'redis',
        'caching' => true,
        'servers' => 
        array (
            1001 => 
            array (
                'scheme' => 'tcp',
                'host' => '121.40.211.61',
                'port' => 6379,
                'database' => 0, //使用第1个db
            ),
        ),
        'life_time' => 604800,
        'compressed' => true,
    ),
    //黑洞缓存 
    'blank' => array(
        'driver' => 'blank',
        'caching' => false,
    ),//END blank
);

return $VAR_ZCACHE;
