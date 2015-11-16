<?php defined('SYSPATH') or die('No direct script access.');

////////////////////////////////////////////////////
// @tool 工具类函数
////////////////////////////////////////////////////

//得到$_GET参数
function get($key, $default=null){
    return isset($_GET[$key]) ? $_GET[$key] : $default;
}

//得到$_POST参数
function post($key, $default=null){
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

//得到$_REQUEST参数, order: 先$_POST,后$_GET
function request($key, $default=null){
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

/********************************** 
  * 截取字符串(UTF-8)
  *
  * @param string $str 原始字符串
  * @param $position 开始截取位置
  * @param $length 需要截取的偏移量
  * @return string 截取的字符串
  * $type=1 等于1时末尾加'...'不然不加
 *********************************/ 
function utfSubstr($str, $position, $length,$type=1){
    $startPos = strlen($str);
    $startByte = 0;
    $endPos = strlen($str);
    $count = 0;
    for($i=0; $i < strlen($str); $i++){
        if($count>=$position && $startPos>$i){
            $startPos = $i;
            $startByte = $count;
        }
        if(($count-$startByte) >= $length) {
            $endPos = $i;
            break;
        }
        $value = ord($str[$i]);
        if($value > 127){
            $count++;
            if($value>=192 && $value<=223) $i++;
            elseif($value>=224 && $value<=239) $i = $i + 2;
            elseif($value>=240 && $value<=247) $i = $i + 3;
            else return null;
        }
        $count++;
    }
    if($type==1 && ($endPos-6)>$length){
        return substr($str, $startPos, $endPos-$startPos)."...";
    }else{
        return substr($str, $startPos, $endPos-$startPos);
    }
}

/**
 * 得到参数,并转义(urltoid)
 *   如果转义后还是字符串, 则返回空(针对字符串 'null');
 *
 * @return number/null
 */
function reqt($key, $method='REQUEST' ){
    $method = strtoupper($method);
    $default = null;
    
    switch($method){
        case 'REQUEST':
            $arr =& $_REQUEST; 
            break;
        case 'POST':
            $arr =& $_POST;
            break;
        case 'GET':
            $arr =& $_GET;
            break;
        default:
            return $default;
    }
    if( isset($arr[$key]) ){
        $result = urltoid( $arr[$key] );
        if( !is_array($result) AND false===strpos($result, ',') ){
            //非数组形式入参,预判断
            return is_numeric($result) ? $result : $default;
        }else
            return $result;
    }
    return $default;
}//END func reqt

/**
 * 获得当前url
 *
 * @return string
 */
function current_url(){
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
    $url .= $_SERVER["SERVER_NAME"].(  (80==$_SERVER["SERVER_PORT"]) ? '' : (':'.$_SERVER["SERVER_PORT"]) )
        .request_uri();
    return $url;
}

/**
 * 获取 _SERVER['REQUEST_URI'] 值的通用解决方案
 *
 */
function request_uri(){
    if (isset($_SERVER['REQUEST_URI'])){
        $uri = $_SERVER['REQUEST_URI'];
    }else{

        if (isset($_SERVER['argv'])){
            $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
        }else{
            $uri = $_SERVER['PHP_SELF'] . 
                (empty($_SERVER['QUERY_STRING']) ? '' : ('?'.$_SERVER['QUERY_STRING']));
        }
    }
    return $uri;
}

/**
 * 获得客户端IP地址, 如果没有 返回 false
 * 
 * @return mixed 
 */
function client_ip(){
    return include('config/client_ip.php');
}//END func ip


//AJAX调用时返回的JSON, 并且强制exit
function jsonReturn($code, $result=null){ //[jsonReturn]
    $return = array( 'status'=>sysStatus($code),
        'result'=>$result, );
    if(!empty($return['result']['data'])){
        $return['result']['data'] = idtourl($return['result']['data']);
    }
    echo json_encode($return);
    exit;
}//END func jsonReturn


function mkdirs($dir, $mode = 0777) { //[mkdirs] 
    if (is_dir($dir) || @mkdir($dir, $mode)){
        chmod($dir, 0777);
        return TRUE; 
    }
    if (!mkdirs(dirname($dir), $mode)) return FALSE; 
    
    $return = @mkdir($dir, $mode);
    if($return) chmod($dir, 0777);
    return $return;
}//END mkdirs

/**
 * 检查一个路径, 返回该绝对路径
 *   如果该路径为目录, 则创建目录;
 *   如果是一个文件, 创建它的上层目录;
 * 
 * @param string $path
 * @param string $dirname
 * @param blooean $is_file
 * 
 * @return string
 */
function check_path($path, $dirname=null, $is_file=false){
    
	mkdirs($path, 0755);
    
	if(empty($dirname)){
        $real_dir = '';
    }else{
        $real_dir = rtrim($dirname, '\\/');
    }

    if( !empty($path) ) {
        $tt = $real_dir.DIRECTORY_SEPARATOR.$path;
        if( is_dir($tt) ){ //存在目录,直接返回
            return $tt;
        }

        $d = preg_split('/\/|\\\/', $path, -1, PREG_SPLIT_NO_EMPTY);
        $d = array_diff($d, array('.',) );
        $count = count($d);
        if($count > 0){
            for($i=0; $i<$count-1; $i++){
                $real_dir .= DIRECTORY_SEPARATOR.$d[$i];
                if( !is_dir($real_dir) ){
                    @mkdir($real_dir, 0777) AND chmod($dir, 0777);
                }
            }
            $real_dir .= DIRECTORY_SEPARATOR.$d[$count-1];
            if($is_file==false){
                //@mkdir($real_dir, 0755);
                @mkdir($real_dir, 0777) AND chmod($dir, 0777);
            }
        }
        return $real_dir;
    }else{
        throw new LogicException('('.__METHOD__.')Error: empty path !');
        return false;
    }
}//END func check_path

/**
 * 取得微妙
 *
 * @return float
 */
function _mtime(){ //[_mtime]
    list($usec, $sec) = explode(' ', microtime());
    return ((float)$usec + (float)$sec);
}//END func _mtime


////////////////////////////////////////////////////
// @encrpt 加密解密、进制转换
////////////////////////////////////////////////////
function dec2any( $num, $base=62, $index=false ) { //[dec2any]
    return include('config/dec2any.php');
}
function any2dec( $num, $base=62, $index=false ) { //[any2dec]
    return include('config/any2dec.php');
}
function base32_encode($input){
    return include('config/base32_encode.php');
}
function base32_decode($input){
    return include('config/base32_decode.php');
}

/**
 * seage 转换 二维数组
 *
 * @param string $str seage文本
 * @param array $single 一维keys
 * @param array $except 排除keys
 *
 */
function _explode($str, $single=null, $except=null){  //[_explode]
    $src = explode(';', $str);
    $pieces = null;
    if(is_array($src)){
        $single = empty($single) ? array() : $single;
        $except = empty($except) ? array() : $except;

        foreach($src as $v){
            if(empty($v)) continue;

            $arr = explode(':', $v);
            if( 2==count($arr) ){
                if( in_array($arr[0], $single) ){
                    $pieces[ $arr[0] ] = $arr[1];
                }
                elseif( false==in_array($arr[0], $except) ) 
                    $pieces[ $arr[0] ][] = $arr[1];
            }
        }
    }
    return $pieces;
}

/**
 * 二维数组 转换为 seage 
 */
function _implode(array $pieces){  //[_implode]
    $str = null;
    foreach($pieces as $k=>$v){
        if( is_array($v) ){
            foreach($v as $vv){
                $str .= $k.':'._addslashes($vv).';';
            }  
        }
        elseif( false!==stripos($v, ',') ){
            $str .= "{$k}:".str_replace(',', ";{$k}:", $v).';';
        }
        else{
            $str .= $k.':'._addslashes($v).';';
        }
    }
    return $str;
}//END func _implode

/**
 * 把特殊符号转换成全角
 */
function _addslashes( $subject ){ //[_addslashes]
    $patterns = array();
    $patterns[0] = '/,/'; //'/,|，/';
    $patterns[1] = '/:/'; //'/:|：/';
    $patterns[2] = '/;/'; //'/;|；/';
    $replacements = array();
    $replacements[0] = '，'; //'_';
    $replacements[1] = '：'; //+';
    $replacements[2] = '；'; //'. ';
    return preg_replace($patterns, $replacements, $subject);
}

function get_token($salt='web_salt', $name='token'){
    return md5(
        mt_rand(1,1000000)
        .  $salt
        .  $name
        .  mt_rand(1,1000000)
    );
}

/**
 *
 * 混淆ID转换为url
 * @param id, version
 */

function idtourl($id, $version = 1) { //[idtourl]	

    if(is_array($id)){
        foreach ($id as $key=>$value) {	
			if(is_array($value)) {
                $id[$key] = idtourl($value);
            }

//            if(is_array($value) || in_array($key,needautoconvert())) {
  //              $id[$key] = idtourl($value);
    //        }		

        }
        return $id;
    }
    elseif(intval($id)>0){

        switch($version) {
        case 1:
            static $convert;
            if(isset($convert[$id])) {
                $url = $convert[$id];
            }else {
                $url = $version . base_convert($id * 2 + 56, 10,36);
                $convert[$id] = $url;
            }
            break;
        default:
            $url = false;
            break;
        }
        return $url;
    }
    return $id;
}

/**
 *
 * 混淆后的URL转换为ID
 * @param $userId
 * @param $check 检查null/undefined
 */
function urltoid($url, $check=false) { //[urltoid]
    if(is_array($url)){
        foreach ($url as $key=>$value) {
            $url[$key] = urltoid($value);
        }
        return $url;
    }
    elseif( false!==stripos($url, ',') ){
        $url = explode(',',$url);
        foreach ($url as $key=>$value) {
            if( empty($value) ){ 
                unset($url[$key]);
                continue;
            }

            $url[$key] = urltoid($value);
        }
        return implode(',',$url);
    }
    else{
        if($check && preg_match('/^(null|undefined)$/i', $url)){
            return false;
        }

        $version = intval(substr($url, 0, 1));

        switch($version) {
        case 1:
            $id =  (intval(base_convert(substr($url, 1), 36, 10)) - 56)/2;
            break;

        default:
            $id = $url;
            break;
        }
        return $id;
    }
}

function echoId($id, $version = 1) { //[echoId]
    echo idtourl($id, $version);
}

function web_setcookie($name, $value, $expire = 0, $path = '/', $domain = NULL, $secure = false, $httponly = false ) {
	$domain = $GLOBALS['DOMAIN'];
	return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}//END func web_setcookie


/**
 * 获得UTF-8版的名字,
 * OR 从URL的参数内容中找到真实的名字
 *
 * @ez:
 *   ifanlimiao.com/u/孙悟空
 *   ifanlimiao.com/u/%E5%AD%99%E6%82%9F%E7%A9%BA
 *   ifanlimiao.com/u/孙悟空【GBK编码】
 *   ifanlimiao.com/u/%CB%EF%CE%F2%BF%D5
 */
function UnameFromUrl($q){  //[UnameFromUrl]
    $enPattern = '/[^a-zA-Z0-9\.\-~_]/'; //非正常query_string http://en.wikipedia.org/wiki/Query_string
    $cnPattern = "/[\x{4e00}-\x{9fa5}]/u"; //utf8中文

    if( preg_match($enPattern,$q) && !preg_match($cnPattern,$q) ) { //utf8
        $q = urldecode($q);
        if( !preg_match("/[\x{4e00}-\x{9fa5}]/u",$q) ) { //!utf8
            $q = mb_convert_encoding( $q, 'UTF-8', 'GBK');
        }
    }
    return $q;
}

function mb_trim($content,$to_encoding='UTF-8',$from_encoding='UTF-8'){
	$content=mb_convert_encoding($content,$to_encoding,$from_encoding);
	$str=mb_convert_encoding("　",$to_encoding,$from_encoding);
	$content=mb_eregi_replace($str," ",$content);
	$content=mb_convert_encoding($content,$from_encoding,$to_encoding);
	$content=trim($content);
	return $content;
}
////////////////////////////////////////////////////
// @auth 判断登录, 原始版本 r9070
////////////////////////////////////////////////////
function get_auth() { //[getAuth]
    return Zauth::instance ()->identity();
}

function is_auth() { //[hasAuth]
    $login_user = Zauth::instance ()->identity();
    return !empty($login_user);
}

//如果未登录那么掉转
function is_auth_or_redirect($redirect=null){
    $is_auth = is_auth();
    if(empty($redirect)){
        $redirect = "/login";
    }
    if(!empty($redirect) && false==$is_auth){ 
        if( stripos($redirect,'login') ){
            web_setcookie('__webref', current_url(), null, '/');
        }

        header("Location: " . $redirect);
        exit;
    }
    return $is_auth;
}//END func is_auth_or_redirect 

//如果登录那么掉转
function is_auth_and_redirect($redirect=null){
    $is_auth = is_auth();
    if(!empty($redirect) && true==$is_auth){
        header("Location: " . $redirect);
        exit;
    }
    return $is_auth;
}//END func is_auth_and_redirect

//用于后端写入日志
function crond_log($message, $filename) {
    $today_path = APPPATH."logs/crond/" . date('Ymd')  . '/';
    
    if(!is_dir($today_path)) {
        mkdir($today_path, 0777);
        chmod($today_path, 0777);
    }
    
    $filename = $today_path . $filename;
    $is_chmod = !file_exists($filename);

    error_log(date('Y-m-d H:i:s') . ' ' .$message . "\n", 3, $filename);
    if(true==$is_chmod){
        chmod($filename, 0777);
    }    

}//END func crond_log


//用于显示 javascript、css
function _script($srcfile){
    if(0===stripos($srcfile, 'http://')){
        return $srcfile;
    }

    $GLOBAL_S_HOST = $GLOBALS['HOST'];

    if('/'!=substr($srcfile, 0, 1)){
        $srcfile = '/'.$srcfile;
    }
    
	$ver = GLOBAL_JSCSS_VERSION;
    $srcfile = $GLOBAL_S_HOST.$srcfile."?{$ver}";

    return $srcfile;
}//END func _script

function _img($imgfile){
	$GLOBAL_S_HOST = $GLOBALS['HOST'];

	if(strpos($imgfile,$GLOBAL_S_HOST)!==false ){
		return $imgfile;	
	}
    
    if('/'!=substr($imgfile, 0, 1)){
        $imgfile = '/'.$imgfile;
    }
	
    $imgfile = $GLOBAL_S_HOST.'/img'.$imgfile;
    $ver = GLOBAL_JSCSS_VERSION;
	return $imgfile."?{$ver}";
}


function url(array $params, $route_name=null) {  //[url]

    if( empty($params['controller']) ){
    	$params['controller'] = Request::current()->controller();
    }
    if( empty($params['action']) ){
        $params['action'] = Request::current()->action();
    }

    $route_name = $params['controller'] . '_' . $params['action'];

	$_routes_all = Route::all();
	
	if(isset($_routes_all[$route_name])) {
			$route = Route::get($route_name);
	}else{
		if( empty($params['controller']) ){
	    	$params['controller'] = Request::current()->controller();
		}
		
		if(isset($_routes_all[$params['controller']])) {
			$route = Route::get($params['controller']);
		}else {
			$route = Route::get('default');
		}
	}
	
	return '/' . $route->uri($params);
}//END func url

//获取给定日期的上下个月份
//给定日期形如：2015-05
//$sign=1上个月，$sign=0,下个月
function getmonth($date,$sign=0){
	$tmp_date = explode('-',$date);
	if(count($tmp_date) != 2){
		return null;	
	}
	$tmp_year = $tmp_date[0];
	$tmp_month = $tmp_date[1];
	if($sign == 0){
		//得到当前月的下一个月
		$tmp_nextmonth=mktime(0,0,0,$tmp_month+1,1,$tmp_year);  
		return $fm_next_month=date("Y-m",$tmp_nextmonth); 
	}else{
		//得到当前月的上一个月  
		$tmp_forwardmonth=mktime(0,0,0,$tmp_month-1,1,$tmp_year); 
		return $fm_forward_month=date("Y-m",$tmp_forwardmonth);  
	}
}





//写SQLstat日志
function sqlstat_log($statement, $time = 0, $action = '', $dsn = ''){
    $time *= 1000;
    $statement = str_replace("\n", '', $statement);
    $msg       = '[[[' . $dsn . ']]]'.$action.'{{{' . $statement . '}}} <<<' . @$_SERVER['REQUEST_URI'] . '>>>((('.$time.')))';
    
    $LOG_FILE = 'sqlstat.log';

    crond_log($msg, $LOG_FILE);
}//END func log

/** 
 * 随机出现一个http代理
 *   默认 $always_rand = false, 即单个请求内只生成唯一一个http地址;
 *   如果想要不同的地址, 设置入参为 rand_http_proxy(true);
 */
function rand_http_proxy($always_rand=false){
    return include('config/rand_http_proxy.php');
}//END func get_http_proxy

//END FILE
