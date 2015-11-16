<?php
/**
 * Memcached 缓存操作 (php-memcached)
 *
 * @filesource phpmencached.class.php
 * @package peck
 * @subpackage _cache
 * @version $id: 0.4, utf8, Mon Dec 14 22:11:28 CST 2009
 * @author LD King <kldscs[at]gmail.com>
 * @copyright Copyleft (D.) 2007 - 2009 MiFunny China Inc.
 * @link http://mifunny.info/
 * @see
 *   http://pecl.php.net/package/memcached
 *   http://cn.php.net/manual/en/class.memcached.php 
 */
class Zcache_Memcached extends Memcached{ 
    
	 /**
     * 默认的缓存策略
     *
     * servers - 缓存服务器配置，参看$_default_server, 允许多个缓存服务器;
     * 			host - 缓存服务器地址或主机名;
     * 			port - 缓存服务器端口;
     * 			weight - 服务器权重, 越大越好;
     * 		array( 'host' => '127.0.0.1', 'port' => '11211', 'weight' => 99, ),
     * 
     * compression - 是否压缩缓存数据;
     * lifetime - 缓存有效时间, 如果设置为 0 表示缓存永不过期;
     * persistent - 是否使用持久连接;
     * encoding_key - 是否加密Key, 默认false;
     * 
     * @var array
     */
	protected $_default_policy = array(
        'servers' => array(),
        'compression' => true,
        'lifetime' => 900,
        'persistent' => true,
        'encoding_key' => false,
        'persistent_id' => __CLASS__,
    );
	
    public function __construct(array $policy=null){
        if (!extension_loaded('memcached')){
            throw new LogicException("Error: Please Configure Memcached extension extension first!");
            return false;
        }
    
        $this->_default_policy = array_merge($this->_default_policy, $policy);
        //parent::__construct( $this->_default_policy['persistent_id'] );
        parent::__construct();
		//是否压缩
		$this->setOption(Memcached::OPT_COMPRESSION, $this->_default_policy['compression']);
		//长连接
		$this->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, $this->_default_policy['persistent']);
		//服务器
		if( !empty($this->_default_policy['servers']) ){
			if( !$this->addServers( $this->_default_policy['servers'] ) )
				throw new RuntimeException("Error: add cache servers failed, check your servers array.");
		}else{
			throw new LogicException("Error: no cache server add.");
        }
    }
    
    /**
     * Store an item
     * Memcached::set() stores the value on a memcache server under the specified key. The expiration parameter can be used to control when the value is considered expired.
     *
     * The value can be any valid PHP type except for resources, because those cannot be represented in a serialized form. If the Memcached::OPT_COMPRESSION option is turned on, the serialized value will also be compressed before storage.
    *
    */
    public function set($key, $value, $expiration=null){
        $lifetime = $this->getLifetime($expiration);
        return parent::set($key, $value, $lifetime); 
    }
    
    /**
     * Store multiple items
     * Memcached::setMulti() is similar to Memcached::set(), but instead of a single key/value item, it works on multiple items specified in items. The expiration time applies to all the items at once.
     *
     */
    public function setMulti(array $items , $expiration=null){
        $lifetime = $this->getLifetime($expiration);
        return parent::setMulti($items, $lifetime);
    }
    
    public function getLifetime($lifetime=null){
        //return is_null($lifetime) ? $this->_default_policy['lifetime'] : $lifetime; 
        $lifetime = is_null($lifetime) ? $this->_default_policy['lifetime'] : $lifetime;
        $time = time();
        if( $time>$lifetime ){
            $lifetime += $time; 
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
    public function clean($mode=0, $tags = array()){
        return $this->flush();
    }

    /**
     * 返回真实的cache对象
     */
    public function trueblood(){
        return self;
    }

}//END class memcached


