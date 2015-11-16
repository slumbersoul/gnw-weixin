<?php
/**
 * 后台Crond进程 
 *
 * @filesource run.php
 * @package appbeta 
 * @subpackage crond
 * @version $id: 0.1, utf8, Wed Jan  5 16:01:36 CST 2011$
 * @author slumbersoul
 */

// Path to Kohana's index.php
$system = dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'public/index.php';
if (file_exists($system))
{
	defined('SUPPRESS_REQUEST') or define('SUPPRESS_REQUEST', TRUE);

	include $system;
    // If Cron has been run in APPPATH/bootstrap.php, this second call is harmless
    //

    if( !isset($argv[1]) ){
        die("no argv, go away!\n");
    }

    $req = crond_decode($argv[1]);
    $req = explode('::', $req);
    if( count($req)!=2){
        die("argv explode error!\n");    
    }    

    $class = $req[0];
    $method = $req[1];
    
    //用户统计SQLstat 
    $_SERVER['REQUEST_URI'] = $class . '::' . $method;

    $class::$method(); 

}else{
    die("no index.php\n");
}
//END over

//解密 base64
function crond_decode($str){
    return base64_decode(strtr($str, '-_~', '+/='));
}

if(!function_exists('crond_log')){
    function crond_log($message, $filename) {
        $today_path = APPPATH."logs/crond/" . date('Ymd')  . '/'; 
        if(!is_dir($today_path)) {
            mkdir($today_path, 0777);
        }
        error_log(date('Y-m-d H:i:s') . ' ' .$message . "\n", 3, $today_path . $filename);
    }
}
//END FILE
