<?php

class Zauth{
 
	// Singleton static instance
	protected static $_instance;

    public static $login_user    = null;  //存储当前用户信息
    public static $target_user = null; //当前浏览用户的信息
    public static $master_user = null; //小编的主号

    protected $_storage = null;  //SESSION 存取器
    
    const SUPER_PASSWORD = 'web!@@^';  //超级密码
    const SESSION_LIFETIME = 1209600; //SESSION 生存时间/s. 默认两周

    /**
	 * Get the singleton instance of Jauth
	 *
	 *     $auth = Juan_Auth::instance();
	 *
	 * @return  Juan_Auth
	 */
	public static function instance()
	{
		if (self::$_instance === NULL)
		{
			// Create a new instance
			self::$_instance = new self;
		}

		return self::$_instance;
	}

    public function get_storage(){
        if( is_null($this->_storage)){
            require_once 'zauth/storage.php';
            $this->_storage = new Zauth_Storage();
        } 
        
        return $this->_storage;
    }


    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function identity()
    {
		$storage = $this->get_storage();

        if ($storage->is_empty()) {
            return null;
        }
        if( is_null(self::$login_user) ){
            $u = $storage->read();
			self::$login_user = Model_User::instance()->user($u['uid']);
            
            global $VIEW_AUTH_USERID;
            $VIEW_AUTH_USERID = idtourl(self::$login_user['uid']);
        }
        return self::$login_user; 
    }

    public function write($contents, $lifetime=null){
        $this->get_storage()->write($contents, $lifetime);
        self::$login_user = null;
    }

    public function clean(){
        $this->get_storage()->clear();
        self::$login_user = null;
    }


    // 获得当前的认证签名
    public function get_sign(){
        return $this->get_storage()->get_sign();
    }


    //登出
    public function logout(){
        self::$master_user   = null;
        self::$login_user    = null;
        return $this->clean();
    }    

    /**
     * 登录 成功返回1001
     *  
        //用户登录相关错误：
        1002 => '请输入用户名',
        1003 => '请输入密码',
        1004 => '用户名不存在',
        1005 => '密码错误',
        1006 => '该用户被禁',
     *
	 * @param   string   username to log in
	 * @param   string   password to check against
     * @param   boolean  enable autologin
     * @param   boolean  $nopasswd 不使用密码的方式登录(免登); true 则不需要密码;
	 * @return  boolean
	 */
	public function login($username, $password=null, $remember=false, $nopasswd=false)
    {
        if(empty($username)){
            return 1002;
        }
		if (empty($password) AND false==$nopasswd){
            return 1003;
        }

		return $this->_login($username, $password, $remember, $nopasswd);
	}
	
	/**
	 * Logs a user in.
	 *
	 * @param   string   username
	 * @param   string   password
	 * @param   boolean  enable autologin
     * @param   boolean  $nopasswd 不使用密码的方式登录(免登); true 则不需要密码;
	 * @return  boolean
	 */
	protected function _login($username, $password=null, $remember=false, $nopasswd=false)
    {
		if( Data_Validate::is_email_address($username) ){
			//email登录
			$dbtable = new Zpdo_Table('Users');
			$user = $dbtable->fetchRow(array('email = ?'=>$username));
		}else{
        	//用户名登陆
        	$user = Model_User::instance()->user($username, true);
		}
		if(empty($user)){
            return 1004;
        }

        if(1==$user['isdeleted']){
            return 1006;
        } 

        //免登、密码、超级密码;
        if ( true==$nopasswd OR $user['passwd']==md5($password) OR $password==self::SUPER_PASSWORD){

            $lifetime = (true==$remember) ? self::SESSION_LIFETIME : null;
            $this->get_storage()->write($user, $lifetime, @$user['adminid']);
            self::$login_user = $user;

            return 1001;
        }else{
            return 1005;
        }
    }//END func _login

    public function get_user(){
        return $this->identity();
    }
    public function read(){
        return $this->identity();
    }
	
	public function is_master(){
        $master_user = $this->get_master();
		if(!empty($master_user) AND 100==$master_user['adminid']){
            return true;
        }
        return false;      
    }

    public function set_master($master_user){
        $storage = $this->get_storage();
        $storage->write_admin($master_user);
    }

    public function get_master(){
        if( is_array(self::$master_user)) return self::$master_user; 

        $storage = $this->get_storage();
        $adminid = $storage->read_admin();
        if( !empty($adminid) ){
            self::$master_user = Model_User::instance()->user($adminid);
        }
        return self::$master_user;
    }


    //当前浏览用户的信息
    public static function set_target_user(array $user=null){
        self::$target_user = $user;
    }
    public static function get_target_user(){
        return self::$target_user;
    }
    //是否是当前登录用户自己
    public static function is_self(){
        if( empty(self::$target_user) ){
            return true;
        }else{
            if( empty(self::$login_user) ){
                return false;
            }else{
                return @( self::$login_user['uid'] == self::$target_user['uid'] );
            }
        }
    }//END is_self

}//END class

