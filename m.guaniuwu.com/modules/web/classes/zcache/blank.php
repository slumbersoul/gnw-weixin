<?php
/**
 * 空白的PHP-Memcached对象
 *
 * @filesource CacheBlank.php
 * @package peck
 * @subpackage _cache
 * @version $id: 0.2, utf8, Mon Dec 14 12:07:01 CST 2009
 * @author LD King <kldscs[at]gmail.com>
 */
class Zcache_Blank{
    
    public function __construct ($persistent_id=null){}
    
    public function __call($method, $arg_array){
        return false;
    }

}//END inter

