<?php
/**
 * 单件实例化 p 
 *
 * @filesource _db.class.php
 * @package peck
 * @subpackage _db
 * @version $id: 0.3, utf8, Sun Dec 20 21:25:28 CST 2009
 * @author LD King <kldscs[at]gmail.com>
 * @copyright Copyleft (D.) 2007 - 2010 MiFunny China Inc.
 * @link http://mifunny.info/
 * @example 
 * 
 *   //数据库配置
 *   $GLOBALS['__configs']['database'] => array(
        //第一个数据库连结
        'db0' => array(
            //数据库驱动, 参见 http://www.php.net/manual/en/pdo.drivers.php
		    'dsn' => 'mysql:dbname=test;host=locahost;port=3306',
		    'username' => 'test', 
		    'password' => 'test',
            'boost' => array(
                'SET NAMES utf8 COLLATE utf8_general_ci;',
                ), //连接既执行的一些SQL
		    //额外选项, 参见 http://www.php.net/manual/en/pdo.setattribute.php
    	    'options' => array( 
			    'ATTR_PERSISTENT' => FALSE, //是否使用长连接 	
		        'MYSQL_ATTR_USE_BUFFERED_QUERY' => TRUE,
   			    ),
            ),

            //第二个数据库连接
            'db1' => array(
                'dsn' => 'sqlite::memory:',
                'boost' => array(
                    'PRAGMA encoding = "UTF-8"',
                ), //连接既执行的一些SQL
            ),
		);
 * 
 */

class Zpdo {
    /**
     * 
     * 现对象存储在
     *   $GOBALS['__objects']['database:mysql'];
     */
	
	private function __construct(){}
	private function __clone(){}
	
	/**
	 * 获得静态对象实例
     *
	 * @return self
	 */
    public static function instance($offset=null){
        $dkey = empty($offset) ? 'default' : $offset;
        $key = 'database:'.$dkey;   
        $oo = '__objects';

        if( !empty($GLOBALS[$oo][$key]) 
          && is_object($GLOBALS[$oo][$key]) ){
            return $GLOBALS[$oo][$key];
        }

		$policy = Kohana::$config->load('zpdo')->$dkey;
        foreach(array('dsn', 'username', 'password', 'boost', 'options') as $k){
            $policy[$k] = array_key_exists($k, $policy) ? $policy[$k] : null;
        }

	try {
        	$object = new Zpdo_Natural($policy);
	}catch(Exception $e) {
		if(is_null($object) && $dkey != 'default') {
			return Zpdo::instance();		
		}
		throw $e;
	}
        //循环执行boost中的SQL语句
        if( 0<count($policy['boost']) ){
            foreach($policy['boost'] as $v){
                $object->exec($v);
            }
        }//END boost
        
        $GLOBALS[$oo][$key] = $object;
        return $GLOBALS[$oo][$key];
	}//END func object

    /**
     * 重新连接数据库
     *
     */
    public static function reconnect($offset=null){
        $dkey = empty($offset) ? 'default' : $offset;
        $key = 'database:'.$dkey;   
        $oo = '__objects';
        if( !empty($GLOBALS[$oo][$key]) ){
            //unset($GLOBALS[$oo][$key]);
            $GLOBALS[$oo][$key] = null;
        } 
        return self::instance($offset);
    }

}//END class 

