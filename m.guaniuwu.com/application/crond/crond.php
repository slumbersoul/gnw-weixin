<?php
/**
 * 读取配置文件, 并且重写 
 *
 * @filesource crond.php
 * @package appbeta 
 * @subpackage crond 
 * @version $id: 0.1, utf8, Wed Jan  5 14:13:45 CST 2011$
 * @author slumbersoul
 */
global $CONFIG_INI, $CONFIG_ARR, $CONFIG_CONTENT;
$CONFIG_INI = dirname(__FILE__) . DIRECTORY_SEPARATOR;
$CONFIG_SH  = dirname(__FILE__) . DIRECTORY_SEPARATOR;
if( !empty($argv[1]) ){
    if(false!==strpos($argv[1], '::')){
//1.测试单个方法
        $argv[1] = crond_encode($argv[1]); 
        include( 'run.php' ); 
        die("\n\nEND SINGLE TEST!\n\n");
    }else{
//2.其他环境
        $tmp_file = pathinfo(str_replace(' ', '', $argv[1]),PATHINFO_FILENAME);
        $CONFIG_INI .= $tmp_file . '.ini';
        $CONFIG_SH  .= '/lock/' . $tmp_file . '.sh';
    }
}else{
//3.默认( 后台任务 )
    die("\n\nNO CROND HERE!\n\n");
}

$CONFIG_ARR = parse_ini_file($CONFIG_INI, true);
$CONFIG_CONTENT = '';
$CONFIG_SHELL_CONTENT = "#!/bin/bash\n";
$DEFAULT_TIME = 180; //默认轮转时间 180s.

if( empty($CONFIG_ARR) ){
    exit('empty ini, bye!');
}

//循环 check 每一个配置
foreach($CONFIG_ARR as $k=>$v){
    $CONFIG_CONTENT .= "[{$k}]\n; 程序中的类、方法(必须是静态D, 且无参数)\n";
    $CONFIG_CONTENT .= "method = \"{$v['method']}\"\n";
    
    if( !empty($v['time']) && is_numeric($v['time']) ) {
        $v['time'] = (int)$v['time'];
    }else{
        $v['time'] = $DEFAULT_TIME;
    }
    $CONFIG_CONTENT .= "; 时间以秒计 s.\ntime = {$v['time']}\n";
    
    if( 'on'!=$v['enable'] ){
        $v['enable'] = 'off';
    }
    $CONFIG_CONTENT .= "; 是否开启 on/off\nenable = \"{$v['enable']}\"\n";
        
    $v['lock'] = crond_encode($v['method']);
    $CONFIG_CONTENT .= "; lock文件名\nlock = \"{$v['lock']}\"\n";

    if( !empty($v['desc']) ){
        $v['desc'] = strtr($v['desc'], '"', '”');
        $CONFIG_CONTENT .= "; 该任务的描述\ndesc = \"{$v['desc']}\"\n";
    }
    $CONFIG_CONTENT .= "\n\n";

    $CONFIG_SHELL_CONTENT .= "nohup sh run.sh {$v['lock']} {$v['time']} {$v['enable']} &\n";
}
//END for
file_put_contents($CONFIG_INI, $CONFIG_CONTENT);
file_put_contents($CONFIG_SH,  $CONFIG_SHELL_CONTENT);

echo "命令行执行完成, 请手工退出 ... \n";
exec( "sh {$CONFIG_SH}" );

// 采用64位加密
function crond_encode($str){
    return strtr(base64_encode($str), '+/=', '-_~');
}

