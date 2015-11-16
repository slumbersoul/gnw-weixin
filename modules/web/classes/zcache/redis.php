<?php
/**
 * Redis 缓存操作 (predis)
 *
 * @filesource redis.php
 * @package mogujie
 * @subpackage zcache
 * @version $id: 0.4, utf8,  2011年 8月20日 星期六 09时13分32秒 CST
 * @author LD King <kldscs[at]gmail.com>
 * @see
 *     https://github.com/nrk/predis
 */
require_once MODPATH . 'predis/lib/Predis.php';

class Zcache_Redis{ 
    
    protected $_redis;
    
    //要使用gzcompress压缩内容么; 2011-08-29 为防止多个实例互相干扰,改成私有属性;
    protected $gzcompress_level = 1; //从速度考虑,使用最快的1 

    /**
     * 默认的缓存策略
     *
     * servers - 缓存服务器配置，参看$_default_server, 允许多个缓存服务器;
     *          'scheme' => 'tcp',
     *          'host'   => '10.0.0.1',   - 缓存服务器地址或主机名;
     *          'port'   => 6379,         - 缓存服务器端口;
     *          'database'     => 0             - 选择那个数据库
     * 		array( 'scheme'=>'tcp', 'host' => '127.0.0.1', 'port' => '11211', 'database'=>0 ),
     * 
     * compressed   - 是否压缩缓存数据;
     * lifetime     - 缓存有效时间, 如果设置为 0 表示缓存永不过期;
     *
     * @var array
     */
    protected $_default_policy = array(
        'servers' => array(),
        'compressed' => true,
        'lifetime' => 900,
        'persistent' => true,
        'dsn' => '', //当前链接的string形式
    );

    public function __construct(array $policy=null){
        $this->_default_policy = array_merge($this->_default_policy, $policy);

        $ser = (1==count($this->_default_policy['servers']))
            ? array_pop($this->_default_policy['servers'])
            : $this->_default_policy['servers']; 

        $this->_redis = new Predis\Client( $ser ); 
        $this->_default_policy['dsn'] = "{$ser['scheme']}://{$ser['host']}:{$ser['port']}/{$ser['database']}";
        
        if(false==@$this->_default_policy['compressed']){
            $this->gzcompress_level = 0;
        }
    }//END __construct

    /**
     * Store an item
     * 
     * @param int $expiration 
     *    null 默认时间
     *    -1   没有过期时间,即永久存在
     * Predis::set() stores the value on a memcache server under the specified key. The expiration parameter can be used to control when the value is considered expired.
     *
     * The value can be any valid PHP type except for resources, because those cannot be represented in a serialized form. If the Predis::OPT_COMPRESSION option is turned on, the serialized value will also be compressed before storage.
     *
     */
    public function set($key, $value, $expiration=null, $encode=true){
        try{
            $lifetime = 0>$expiration ? -1 : $this->getLifetime($expiration);

            if( true==$encode ){ 
                $value = json_encode($value);
                if(0<$this->gzcompress_level && !is_null($value) ){
                    $value = gzcompress($value, $this->gzcompress_level);
                }
            }//END encode

            //setex 必须是过期时间, 非UNIX时间戳
            if( -1==$expiration ){
            	return $this->_redis->set($key,$value);
            }else 
                return $this->_redis->setex($key,$lifetime,$value);

        }catch(Exception $e){
            crond_log("set[{$key}]: ".$e->getMessage(), "redis.log");
            return false;
        }
    }//END func set

    /**
     * Set key to hold string value if key does not exist. In that case, it is equal to SET. When key already holds a value, no operation is performed. SETNX is short for "SET if Not eXists".
     */
    public function add($key, $value, $expiration=null, $encode=true){
        try{
            $lifetime = $this->getLifetime($expiration);

            if( true==$encode ){
                $value = json_encode($value);
                if(0<$this->gzcompress_level && !is_null($value) ){
                    $value = gzcompress($value, $this->gzcompress_level);
                }
            }//END encode

            $a = $this->_redis->setnx($key, $value);
            $b = false;
            if( $a ){
                $b = $this->_redis->expire($key, $lifetime);
            }
            return ($a && $b);

            //$res = $this->_redis->pipeline(function($pipe) {
            //$pipe->setnx($key, $value);
            //$pipe->expire($key, $lifetime);
            //});
            //return (@$res[0] && @$res[1]);
        }catch(Exception $e){
            crond_log("add[{$key}]: ".$e->getMessage(), "redis.log");
            return false;
        }
    }//END func add


    /**
     *  Retrieve an item
     *  
     *  @return mixed 失败则返回false
     */ 
    public function get($key, $encode=true){
        try{
            $res = $this->_redis->get($key);
            
            if( false==$encode ){
                return is_null($res) ? false : $res;
            }//END encode

            if(0<$this->gzcompress_level && !is_null($res) ){
                $res2 = gzuncompress( $res );
                $res  = (false===$res2) ? $res : $res2;
            }

            return is_null($res) ? false : json_decode($res,true);

        }catch(Exception $e){
            crond_log("get[{$key}]: ".$e->getMessage(), "redis.log");
            return false;
        }
    }//END func get


    /**
     * Store multiple items
     * Predis::setMulti() is similar to Predis::set(), but instead of a single key/value item, it works on multiple items specified in items. The expiration time applies to all the items at once.
     *
     */
    public function setMulti(array $items , $expiration=null, $encode=true){
        global $Zcache_Redis_lifetime, $Zcache_Redis_items, $gzcompress_level;

        //@todo 暂时使用全局变量这种土方法;
        $gzcompress_level      = $this->gzcompress_level;
        $Zcache_Redis_lifetime = $this->getLifetime($expiration);
        $Zcache_Redis_items    = $items;
        unset($items);

        if( true==$encode ){
            array_walk($Zcache_Redis_items, function(&$v){ global $gzcompress_level; $v=json_encode($v);if(0<$gzcompress_level) $v=gzcompress($v,$gzcompress_level);});
        }//END encode

        $res = $this->_redis->pipeline(function($pipe){
            global $Zcache_Redis_lifetime, $Zcache_Redis_items;
            $pipe->mset($Zcache_Redis_items);
            foreach($Zcache_Redis_items as $k=>$v) $pipe->expire($k, $Zcache_Redis_lifetime);
        });

        unset($Zcache_Redis_lifetime);
        unset($Zcache_Redis_items);
        return ( @$res[0] );
    }//END func setMulti

    /** 
     * Retrieve multiple items
     */
    public function getMulti(array $keys, $encode=true){
        try{
            $items = $this->_redis->mget($keys); 

            $res = array();
            if( !is_array($items) ) return null;

            foreach($items as $k=>$v){
                unset($items[$k]);
                $key = array_shift($keys);
                if(is_null($v)) continue;
                
                if( true==$encode ){
                    if(0<$this->gzcompress_level){ 
                        $v = gzuncompress($v);
                    }
                    $res[$key] = json_decode($v, true); 
                }
                else{
                    $res[$key] = $v; 
                }//END encode
            }

            return $res;
        }catch(Exception $e){
            crond_log("getMulti[{$key}]: ".$e->getMessage(), "redis.log");
            return false;
        }
    }//END func getMulti


    /**
     * 自增操作 +1 || @gzuncompress=0
     * @return the value of key after the increment
     */
    public function incr($key, $expiration=null){
        try{
            global $Zcache_Redis_lifetime, $Zcache_Redis_key;
            $Zcache_Redis_lifetime = $this->getLifetime($expiration);
            $Zcache_Redis_key      = $key;

            $res = $this->_redis->pipeline(function($pipe){
                global $Zcache_Redis_lifetime, $Zcache_Redis_key;
                $pipe->incr($Zcache_Redis_key);
                $pipe->expire($Zcache_Redis_key, $Zcache_Redis_lifetime);
            });
            unset($Zcache_Redis_lifetime);
            unset($Zcache_Redis_key);
        
            return intval($res[0]);
        }catch(Exception $e){
            crond_log("incr[{$key}]: ".$e->getMessage(), "redis.log");
            return false;
        }
    }//END func incr 

    /**
     * 自减操作 +1 || @gzuncompress=0
     * @return the value of key after the decrement
     */
    public function decr($key, $expiration=null){
        try{
            global $Zcache_Redis_lifetime, $Zcache_Redis_key;
            $Zcache_Redis_lifetime = $this->getLifetime($expiration);
            $Zcache_Redis_key      = $key;

            $res = $this->_redis->pipeline(function($pipe){
                global $Zcache_Redis_lifetime, $Zcache_Redis_key;
                $pipe->decr($Zcache_Redis_key);
                $pipe->expire($Zcache_Redis_key, $Zcache_Redis_lifetime);
            });
            unset($Zcache_Redis_lifetime);
            unset($Zcache_Redis_key);

            return intval($res[0]);
        }catch(Exception $e){
            crond_log("decr[{$key}]: ".$e->getMessage(), "redis.log");
            return false;
        }
    } //END func decr

    /**
     * 递增操作, 可以设置偏移量;
     *   @注意 本函数设置的自增字段将永不过期,使用前请先确认需求!
     *
     */
    public function zincr($key, $offset=1){
        return $this->_redis->incrby($key, intval($offset) ); 
    }
    /**
     * 递减操作, 可以设置偏移量;
     *   @注意 本函数设置的自减字段将永不过期,使用前请先确认需求!
     */
    public function zdecr($key, $offset=-1){
        return $this->_redis->decrby($key, intval($offset) ); 
    }

    /**
     * 设置key的过期时间
     *
     * @param $expiration 过期时间,按秒记;
     */
    public function expire($key, $expiration=null){
        $expiration = $this->getLifetime($expiration);
        return $this->_redis->expire($key, $expiration);
    }

    /**
     *  Delete an item
     */
    public function delete($key){
        return $this->_redis->del($key);
    }


    //redis 传入的是过期的时间;
    //$lifetime 是过期的时间, 比如 15s 
    public function getLifetime($lifetime=null){
        $lifetime = is_null($lifetime) ? $this->_default_policy['lifetime'] : $lifetime;
        $time = time();
        if( $time<$lifetime ){
            $lifetime -= $time; 
        }

        return $lifetime;
    } //END func getLifetime

    //Zend_Cache Compatiable 
    public function load($id, $doNotTestCacheValidity = false){
        return $this->get($id);
    }
    public function save($data, $id, $tags = array(), $specificLifetime = false){
        return $this->set($id, $data, ( false==$specificLifetime ? null : $specificLifetime ) );
    }
    public function remove($id){
        return $this->delete($id);
    }
    /**
     * 太危险, 最好不要执行;
     *   刷新当前的db
     */
    public function clean(){
        return $this->_redis->flushdb();
    }

    /**
     * 返回真实的cache对象
     */
    public function trueblood(){
        return $this->_redis;
    }



    ////////////////////////////
    // 内存队列, 先进先出!
    //
    // 从队头入队, 从队尾出队;
    ////////////////////////////
    
    //默认队列名
    public static $queue_pool = 'mq';

    //改变队列
    public function changeQueuePool( $queue_pool = 'mq' ){
        self::$queue_pool = $queue_pool;
        return $this;    
    }//END func changeQueuePool


    //固定长度队列 可以越界的保留长度; 该值可以减少对队列的频繁操作;
    const QUEUE_LENGTH_CROSS = 20;

    /** 
     * 把变量入队, 不能是空变量;
     *  
     * @param int $queue_length 如果该值大于0,则触发判断: 当前队列超过 
     *  
     * Insert all the specified values at the head of the list stored at key. If key does not exist, it is created as empty list before performing the push operations. When key holds a value that is not a list, an error is returned.
     */
    public function push($value, $queue_pool=null, $queue_length=0){
        if(is_null($value)) return false;
        
        $_s = microtime(true);//开始时间


        $queue_pool = is_null($queue_pool) ? self::$queue_pool : $queue_pool;
 
        //是否启用压缩, 当value大于10k 性能将可能下降;
        $value = json_encode($value);
        if(0<$this->gzcompress_level ){
            $value = gzcompress($value, $this->gzcompress_level);
        }
        
        $res = $this->_redis->lpush($queue_pool, $value);

        //如果是固定长度的队列, 切除多余部分
        if( 0<$queue_length && $res>($queue_length+self::QUEUE_LENGTH_CROSS) ){
            $this->_redis->ltrim($queue_pool, 0, $queue_length-1);
        }

        $_e = microtime(true);//结束时间
        $this->log_queue($queue_pool, $_e-$_s, 'push');

        return $res;
    }//END func push

    //同push, 多个变量, redis2.4 之后lpush支持多变量, 以下方法兼容2.2版本
    public function pushMulti(array $items, $queue_pool=null){
        global $Zcache_Redis_MQ_items, $Zcache_Redis_MQ_pool, $gzcompress_level;

        $_s = microtime(true);//开始时间
        
        //@todo 暂时使用全局变量这种土方法;
        $gzcompress_level        = $this->gzcompress_level;
        $Zcache_Redis_MQ_pool    = is_null($queue_pool) ? self::$queue_pool : $queue_pool;
        $Zcache_Redis_MQ_items   = $items;
        unset($items);

        $res = $this->_redis->pipeline(function($pipe){
            global $Zcache_Redis_MQ_items, $Zcache_Redis_MQ_pool, $gzcompress_level;

            foreach($Zcache_Redis_MQ_items as $v){
                if(is_null($v)) continue;

                $v=json_encode($v);
                if(0<$gzcompress_level){
                    $v=gzcompress($v,$gzcompress_level);
                }
                
                $pipe->lpush($Zcache_Redis_MQ_pool, $v); 
            }//END for 
        });

        $_e = microtime(true);//结束时间
        $this->log_queue($Zcache_Redis_MQ_pool, $_e-$_s, 'pushMulti');

        unset($Zcache_Redis_MQ_items);
        unset($Zcache_Redis_MQ_pool);
        return ( @$res[0] );
    }//END func pushMulti


    /**
     * 出队操作
     * Removes and returns the last element of the list stored at key.
     */
    public function pop($queue_pool=null){
        $queue_pool = is_null($queue_pool) ? self::$queue_pool : $queue_pool;
        
        $_s = microtime(true);//开始时间

        $res = $this->_redis->rpop($queue_pool);
        
        if(0<$this->gzcompress_level && !is_null($res) ){
            $res = gzuncompress($res);
        }

        $res = is_null($res) ? null : json_decode($res,true);

        $_e = microtime(true);//结束时间
        $this->log_queue($queue_pool, $_e-$_s, 'pop');

        return $res;
    }//END func pop

    /**
     *  出队,获取队列中的元素值;
     */
    public function popMulti($length=5, $queue_pool=null){
        global $Zcache_Redis_MQ_length, $Zcache_Redis_MQ_pool;
        
        $_s = microtime(true);//开始时间

        //@todo 暂时使用全局变量这种土方法;
        $Zcache_Redis_MQ_pool    = is_null($queue_pool) ? self::$queue_pool : $queue_pool;
        $Zcache_Redis_MQ_length  = $length;
        unset($queue_pool);
        unset($length);
        
        $res = $this->_redis->pipeline(function($pipe){
            global $Zcache_Redis_MQ_length, $Zcache_Redis_MQ_pool;
        
            for($i=0; $i<$Zcache_Redis_MQ_length; $i++){
                $pipe->rpop($Zcache_Redis_MQ_pool); 
            }//END for 
        });

        //if(empty($res)) return null;

        $return = array(); 
        foreach($res as $k=>$v){
            unset($res[$k]);
            if( is_null($v) ) break;

            if(0<$this->gzcompress_level){
                $v = gzuncompress($v);
            }
            $return[] = json_decode($v, true); 
        } 

        $_e = microtime(true);//结束时间
        $this->log_queue($Zcache_Redis_MQ_pool, $_e-$_s, 'popMulti');

        unset($Zcache_Redis_MQ_length);
        unset($Zcache_Redis_MQ_pool);
        return empty($return) ? null : $return;
    }//END func popMulti

    /**
     *  单纯获取队列中的元素值,但它不出队;
     *
     *  @return list of elements in the specified range.
     */
    public function lrange($start=0, $end=-1, $queue_pool=null){
        global $Zcache_Redis_MQ_start, $Zcache_Redis_MQ_end, $Zcache_Redis_MQ_pool;
        
        $_s = microtime(true);//开始时间

        $queue_pool = is_null($queue_pool) ? self::$queue_pool : $queue_pool;
        $res = $this->_redis->lrange($queue_pool, intval($start), intval($end));
        if(empty($res)) return null;

        $return = array(); 
        foreach($res as $k=>$v){
            unset($res[$k]);
            if( is_null($v) ) break;

            if(0<$this->gzcompress_level){
                $v = gzuncompress($v);
            }
            $return[] = json_decode($v, true); 
        } 

        $_e = microtime(true);//结束时间
        $this->log_queue($queue_pool, $_e-$_s, 'lrange');

        return (0==count($return)) ? null : $return;
    }//END func lrange

    /**
     * 当前有多少消息
     */
    public function left($queue_pool=null){
        $queue_pool = is_null($queue_pool) ? self::$queue_pool : $queue_pool;
        
        $left = $this->_redis->llen($queue_pool);
        return intval($left);
    }//END func left


    //记录入队 出队时间;
    protected function log_queue($queue_pool, $time, $action=''){
        sqlstat_log('Queue-'.$queue_pool, $time, $action, @$this->_default_policy['dsn']);
    }//END func log_queue


    /***********************
     * redis hash
     ***********************/
    public static $hash_pool = 'mghash';

    /**
     * 改变hash
     */
    public function changeHash($hash_pool = 'mghash') {
        self::$hash_pool = $hash_pool;
        return $this;
    }

    /**
     * redis hash hset
     * 设置单个key值
     */
    public function hset($key, $value, $hash_pool = null) {
        if (is_null($key) || is_null($value)) {
            return false;
        }
        $_s = microtime(true);
        $hash_pool = is_null($hash_pool) ? self::$hash_pool : $hash_pool;
        $res = $this->_redis->hset($hash_pool, $key, $value);

        $_e = microtime(true);//结束时间
        $this->log_queue($hash_pool, $_e-$_s, 'hset');

        return $res;
    }

    /**
     * redis hash hmset
     * @param $item array key=>value
     * 设置多个key值
     */
    public function hmset(array $items, $hash_pool = null) {
        global $Zcache_Redis_HASH_items, $Zcache_Redis_HASH_pool;

        $_s = microtime(true);//开始时间
        $Zcache_Redis_HASH_pool    = is_null($hash_pool) ? self::$hash_pool : $hash_pool;
        $Zcache_Redis_HASH_items   = $items;
        unset($items);

        $res = $this->_redis->pipeline(function($pipe){
            global $Zcache_Redis_HASH_items, $Zcache_Redis_HASH_pool;

            foreach($Zcache_Redis_HASH_items as $key=>$v){
                if(is_null($v)) continue;
                $pipe->hset($Zcache_Redis_HASH_pool, $key, $v); 
            }//END for 
        });

        $_e = microtime(true);//结束时间
        $this->log_queue($Zcache_Redis_HASH_pool, $_e-$_s, 'hmset');

        unset($Zcache_Redis_HASH_items);
        unset($Zcache_Redis_HASH_pool);
        return ( @$res[0] );
    }

    /**
     * redis hash get
     * 获取key对应的value，如果不存在，返回null
     */
    public function hget($key, $hash_pool = null) {
        if (is_null($key)) {
            return null;
        }
        $hash_pool = is_null($hash_pool) ? self::$hash_pool : $hash_pool;
    
        $_s = microtime(true);//开始时间
        $res = $this->_redis->hget($hash_pool, $key);
        $res = is_null($res) ? null : $res;
        
        $_e = microtime(true);//结束时间
        $this->log_queue($hash_pool, $_e-$_s, 'hget');

        return $res;
    }

    /**
     * redis hash hdel
     * 删除key
     */
    public function hdel($key, $hash_pool = null) {
        if (is_null($key)) {
            return true;
        }
        $hash_pool = is_null($hash_pool) ? self::$hash_pool : $hash_pool;
        
        $_s = microtime(true);//开始时间
        $res = $this->_redis->hdel($hash_pool, $key);
        $_e = microtime(true);//结束时间
        
        $this->log_queue($hash_pool, $_e-$_s, 'hdel');

        return $res;
    }

    /**
     * redis hash hincrby
     */
    public function hincrby($key, $value, $hash_pool = null) {
        if (is_null($key) || is_null($value)) {
            return false;
        }
        $hash_pool = is_null($hash_pool) ? self::$hash_pool : $hash_pool;

        $_s = microtime(true);//开始时间
        $res = $this->_redis->hincrby($hash_pool, $key, $value);
        $_e = microtime(true);//结束时间

        $this->log_queue($hash_pool, $_e-$_s, 'hincrby');

        return $res;
    }

    /**
     * redis hash hgetall
     */
    public function hgetall($hash_pool = null) {
        $hash_pool = is_null($hash_pool) ? self::$hash_pool : $hash_pool;
        $_s = microtime(true);//开始时间
        $res = $this->_redis->hgetall($hash_pool);
        $_e = microtime(true);//结束时间

        $this->log_queue($hash_pool, $_e-$_s, 'hgetall');

        return $res;
    }

    /**
     * redis hash hmincrby
     * 多个key执行hincrby命令
     */
    public function hmincrby($items, $hash_pool = null) {
        global $Zcache_Redis_HASH_items, $Zcache_Redis_HASH_pool;
        $_s = microtime(true);
        $Zcache_Redis_HASH_pool    = is_null($hash_pool) ? self::$hash_pool : $hash_pool;
        $Zcache_Redis_HASH_items   = $items;
        unset($items);

        $res = $this->_redis->pipeline(function($pipe){
            global $Zcache_Redis_HASH_items, $Zcache_Redis_HASH_pool;

            foreach ($Zcache_Redis_HASH_items as $key=>$v) {
                if(is_null($v)) continue;

                $pipe->hincrby($Zcache_Redis_HASH_pool, $key, $v); 
            }
        });

        $_e = microtime(true);//结束时间
        $this->log_queue($Zcache_Redis_HASH_pool, $_e-$_s, 'hmincrby');

        unset($Zcache_Redis_HASH_items);
        unset($Zcache_Redis_HASH_pool);
        return ( @$res[0] );
    }
}//END class
