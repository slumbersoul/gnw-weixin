<?php
/**
 * 数据验证 
 *
 * @filesource validate.php
 * @package mogujie 
 * @subpackage data
 * @version $id: 0.1, utf8, Fri Jan 14 16:20:46 CST 2011$
 *   
 */
class Data_Validate{
    //用户名 黑名单
    const BLACK_UNAME_PATTERN_FILE = 'black_uname_keywords.php';
    
    //获取匹配用户名黑名单的正则
    public static function black_uname_pattern(){
        return include(self::BLACK_UNAME_PATTERN_FILE);
    }


    /** 
     * 用户名是否包含非法词
     *
     * @param $uname 用户名
     * @param &$keyword 作为引用的非法词
     * @return boolean true:包含非法词
     */
    public static function is_uname_illegal($uname, &$keyword=null){
        $result = preg_match( self::black_uname_pattern(), $uname, $m);
        if(0<$result){
            $keyword = array_pop($m);
            return true; 
        }else{
            return false;
        }
    }
    
    /**
     * 验证是否是合法的email地址
     *
     * @param string $mail
     * @return boolean
     */
    public static function is_email_address($mail) { //[valid_email_address]
        $user = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\`\|\{\}~\']+';
        $domain = '(?:(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.?)+';
        $ipv4 = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
        $ipv6 = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';

        //return ( 0 < preg_match("/^$user@($domain|(\[($ipv4|$ipv6)\]))$/", $mail) );
        return ( 0 < preg_match("/^([A-Za-z0-9])([\w\-\.])*@(vip\.)?([\w\-])+(\.)(com|com\.cn|net|cn|net\.cn|org|biz|info|gov|gov\.cn|edu|edu\.cn)$/", $mail) );
    }//END func is_email_address

    public static function is_phone_number($phone){
		if(!is_numeric($phone)){
			return false;	
		}
		if(strlen($phone) != 11){
			return false;	
		}
		$phone_pre = substr($phone,0,2);
		$pre_array = array();
		$pre_array[] = '13';
		$pre_array[] = '15';
		$pre_array[] = '18';
		if(!in_array($phone_pre,$pre_array)){
			return false;
		}	
		return true;
	}
	
	/**
     * 禁用此IP(爬虫...)
     *
     */
    public static function disable_client_ip($ip=null){
        $disable = array('119.6.91.250','114.138.0.2','113.110.78.15','175.169.108.233'); 

        if( in_array($ip, $disable, true)){
            echo '<html><head></head><body><a href="http://www.hao123.com/">http://www.hao123.com/</a></body></html>'; 
            exit; 
        }


    }//END func disable_client_ip

}
//END class

