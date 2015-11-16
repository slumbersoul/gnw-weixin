<?php 
/**
$VAR_ZPDO = array
    (
        'default' => array
        (
            //数据库驱动, 参见 http://www.php.net/manual/en/pdo.drivers.php
            'dsn' => 'mysql:dbname=guangmo;host=localhost;port=3306',
            'username' => 'root', 
            'password' => 'guangmo',
            'boost' => array(
                //'SET NAMES utf8 COLLATE utf8_general_ci;',
            ), //连接既执行的一些SQL
            //额外选项, 参见 http://www.php.net/manual/en/pdo.setattribute.php
            'options' => array( 
                PDO::ATTR_TIMEOUT => 3,
                PDO::ATTR_PERSISTENT => true,	
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            ),
        ),
    );

return $VAR_ZPDO;
*/
