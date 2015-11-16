<?php
/**
 * Auth_Storage
 *   认证存储 
 *
 * @filesource storage.php
 * @package  
 * @subpackage 
 * @version $id: 0.1, utf8, $
 * @author SlumberSoul
 * @copyright Copyleft (S.) 2012 - 2012 SS.
 * @desc
 *   $_COOKIE['_web'] = timeout_userId
 */
class Zauth_Storage{
    //密钥 
    const MCRYPT_SALT        = 'slumber let it be!';
    //用户登录验证的Key
    const AUTH_COOKIE_KEY    = '__slbuserv';
    const AUTH_POST_KEY      = 'sign';
    const AUTH_MASTER_COOKIE_KEY = '__master'; //超级用户权限

    protected $_session  = null;
    //是否为空
    protected $_isEmpty  = true;
    //session生存时间, 2周
    protected $_lifetime = null;
    //最后加密过的签名
    protected $_sign     = null;
    //admin的id
    protected $_adminId= null;

    /**
     * Sets session storage options and initializes session namespace object
     *
     * @param  boolean $auth 是否是验证用户登录
     * @return void
     */
    public function __construct() {
        $sign = null;
        if( !empty($_COOKIE[self::AUTH_COOKIE_KEY])){ //判断用户最后登录是否是两周
            $sign = $_COOKIE[self::AUTH_COOKIE_KEY];
        }

        if( !is_null($sign) ){
            $val = explode('_', $this->decrypt($sign) );
			if( 3<count($val) && is_numeric($val[1]) &&  is_numeric($val[2]) ){
                if( (int)$val[0] > time() ){
                    $this->_session = array( 'uid'=>$val[1], 'lastlogintime'=>$val[2]);
                    $this->_isEmpty = false;
                    $this->_sign    = $sign;
                }
            }
        }//END sign 
    }//END __construct

    //是否为空
    public function is_empty(){
        return $this->_isEmpty;
    }

    //获得加密之后的签名字符串 
    public function get_sign(){
        return $this->_sign;
    }

    //读取当前的session数组
    public function read(){
        return $this->_session;
    }//END read
    
    public function write(array $contents, $lifetime=null, $adminid=0){
       	if( !isset($contents['uid']) || !isset($contents['lastlogintime']) ){
            throw new Exception('error session.');
            return false;     
       	}
       	$this->_session = $contents;
       
       	$lifetime = empty($lifetime)
           ? ( is_null($this->_lifetime) ? null : time()+$this->_lifetime )
           : time()+$lifetime;

       	//如果过期时间不是3点; 重置成3点
       	if( !is_null($lifetime) ){
           	$l_H = date('H',$lifetime);
           	if( 3!=intval($l_H) ){
               	$lifetime = strtotime( str_replace(" {$l_H}:", ' 3:', date('Y-m-d H:m:s',$lifetime)) );
           	}
       	}

       	$prefix = empty($lifetime) ? time()+86400 : $lifetime; //过期时间
       	if( isset($_SERVER["HTTP_USER_AGENT"]) ){
           	$agent_len = strlen($_SERVER["HTTP_USER_AGENT"]);
           	$suffix = (1>$agent_len-12) ? $_SERVER["HTTP_USER_AGENT"]
               	: substr($_SERVER["HTTP_USER_AGENT"], mt_rand(0,$agent_len-12), 12); 
       	}else{
           	$suffix = $prefix;
		}
       	$value = "{$prefix}_{$contents['uid']}_{$contents['lastlogintime']}_{$suffix}";
       	$value = $this->encrypt($value);

       	$this->_isEmpty = false;
       	$this->_sign    = $value;

       	$setc = web_setcookie(self::AUTH_COOKIE_KEY, $value, $lifetime, '/');
       	if( true!=$setc){
       		web_setcookie(self::AUTH_COOKIE_KEY, $value, null, '/');
       	} 
		//如果是adminid, 则写入__master
       	if(100==$adminid){
        	$setc = web_setcookie(self::AUTH_MASTER_COOKIE_KEY, $value, $lifetime, '/');
          	if( true!=$setc){
          		web_setcookie(self::AUTH_MASTER_COOKIE_KEY, $value, null, '/');
          	}
     	}//END $adminid 
        
    }//END write
    
	//添加管理员的cookie信息 方便小编编辑
     public function write_admin(array $contents, $lifetime=null){
         $this->write($contents, $lifetime, 100);
     }//END write admin

    public function read_admin() {
        $admin_sign = null;
        if( !empty($_COOKIE[self::AUTH_MASTER_COOKIE_KEY])){ //判断用户最后登录是否是两周
            $admin_sign = $_COOKIE[self::AUTH_MASTER_COOKIE_KEY];
            if( !is_null($admin_sign) ){
                $val = explode('_', $this->decrypt($admin_sign) );
                if( 3<count($val) && is_numeric($val[1]) &&  is_numeric($val[2]) ){
                    if( (int)$val[0] > time() ){
                        $this->_adminId    = $val[1];
                        return $this->_adminId;
                        }
                }
            }//END sign
        }
        return null;

    }//END  read admin


    //清除当前认证session, 仅仅对cookie有效
    public function clear()
    {
       web_setcookie(self::AUTH_COOKIE_KEY, null, null, '/');
       web_setcookie(self::AUTH_MASTER_COOKIE_KEY, null, null, '/');
	   $this->_isEmpty = true;
       $this->_session = null;
       $this->_sign    = null;
       $this->_adminId = null;
    }

    //记住我
    public function rememberMe($lifetime=null){ //默认两周
        $lifetime = is_null($lifetime) ? 1209600 : $lifetime;
        $this->_lifetime = $lifetime;
            
        if(!$this->_isEmpty){
            $this->write($this->_session, $lifetime);
        }
    }

    //当前session忘记我
    public function forgetMe(){
       if($this->_isEmpty) return;
        
       $this->_lifetime = null;
       $this->write($this->_session);
    }

    //加密
    public function encrypt($text){ 
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, self::MCRYPT_SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
    } 

    //解密
    public function decrypt($text) {
            $value = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, self::MCRYPT_SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 

        return $value;
    } 


}//EOF

