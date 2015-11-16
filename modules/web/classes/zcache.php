<?php
/**
 * 缓存帮助器
 * 
 */
class Zcache {
    const HASH_ON = true;

    protected static $caching = null;
    private function __construct(){}
    private function __clone(){}  

    public static function instance( $offset=null, array $policy=null ) {
        $dkey = empty($offset) ? 'default' : $offset;
        $key = 'cache:'.$dkey;   
        $oo = '__objects';

        if( !empty($GLOBALS[$oo][$key]) 
            && is_object($GLOBALS[$oo][$key]) ){
                return $GLOBALS[$oo][$key];
            }

        if( is_null($policy) ){
			$policy = Kohana::$config->load('zcache')->$dkey;
        }else{
            $policy = isset($policy[$dkey]) ? $policy[$dkey] : current($policy);
        }

        if(isset($policy['caching']) && false==$policy['caching'] ){ 
            $GLOBALS[$oo][$key] = new Zcache_Blank();
            self::$caching = false;
        }else{
            $driver = 'Zcache_' . ucfirst($policy['driver']);
			$GLOBALS[$oo][$key] = new $driver($policy); 
            self::$caching = true;
        }
        return $GLOBALS[$oo][$key];
    }

	// for kohana::cache
	public static function cache($key, $value = null, $lifetime = null) {
		$cache = Zcache::instance();
		if(is_null($value)) {
		    return $cache->get($key);
		}
		
		return $cache->set($key, $value, $lifetime);
	}

    //判断缓存是否已经启动
    public static function caching( $offset=null ){
        self::instance($offset);
        return self::$caching;
    }

    public static function fetchNode($key, $offset=null) {
        return self::instance($offset)->get($key);
    }

    public static function updateNode($key, $value, $offset=null) {
        return self::instance($offset)->replace($value, $key);
    }

    public static function insertNode($key, $value, $offset=null) {
        return self::instance($offset)->set($key, $value);
    }

    public static function deleteNode($key, $offset=null) {
        return self::instance($offset)->delete($key);
    }


    /**
     * 建立对应的内存队列
     *
     * @param $queue_pool 队列的名字
     * @param $offset     配置文件中的key
     */
    public static function mq( $queue_pool = 'tweetpush', $offset='redismq'){
        return self::instance($offset)->changeQueuePool($queue_pool);
    }//END func mq

    /**
     * redis hash
     * @param $hash_pool hash的名
     * @param $offset 配置文件中的key
     */
    public static function hash($hash_pool = 'mghash', $offset = 'redishash') {
        return self::instance($offset)->changeHash($hash_pool);
    }
}//END class
