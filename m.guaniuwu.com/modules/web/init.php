<?php defined('SYSPATH') or die('No direct script access.');

define('APPLICATION_PREPATH', DOCROOT . '..' . DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR);

/**
 * 翻译错误代码
 */
function sysStatus($code=1001){ //[sysStatus]
    $stat = array(

    //***********************  ４位数 **********************************************************
    	1001 => '正常',

        //系统级错误：
        4002 => '数据出错',
        4003 => '调用超时',
        4004 => "未知错误，可能的原因：\n1.网络问题\n2.服务器超时\n3.其它原因",
        4005 => '随机ID生成错误',
        4006 => 'nologin', 					//直接禁访

        //用户登录相关错误：
        1002 => '请输入用户名',  //zauth
        1003 => '请输入密码',  //zauth
        1004 => '用户名不存在',  //zauth
		1005 => '密码错误',  //zauth
		1006 => '该用户被禁',  //zauth
		//用户注册。。。
        1011 => '请输入电子邮件地址',  //con_user reg
		1012 => '昵称只能6~20位，每个中文字算2位字符', //mod_user filter_uname
        1013 => '昵称不能用数字开头',  //mod_user filter_uname
        1014 => '昵称仅支持中文（含繁体）、数字、字母', //mod_user filter_uname
        1015 => '这昵称太热门了，被别人抢走啦，换一个吧', //mod_user filter_uname
        1016 => '昵称名包含非法关键字, 请换一个', //mod_user filter_uname
        1017 => '密码和确认密码不匹配',  //con_user reg
		1111 => '电子邮件地址已经被使用。',//con_user ajaxcheck reg
        1117 => '邮件地址不正确',  //con_user reg ajaxcheck
        1118 => '密码太短啦，至少要6位哦',  //con_user reg
        1119 => '密码过长',   //con_user reg
        1120 => '验证码输入错误，请重新输入',  //con_user reg ajaxcheck
        1121 => '请填写昵称',   //mod_user filter_uname
		1122 => '请输入手机号码', //mode_user filter_phone
		1123 => '请输入正确的手机号码', //mode filter_phone
		1124 => '身份证输入错误',
		1125 => '手机号码重复',

		1022 => '您没有登录，不能执行该操作',
        1023 => '用户名不知道，请重新登录',
		1024 => '登录超时，请重新登录',
		1025 => '修改失败，请重新修改',

        1099 => '您的权限不足，无法执行该操作',
      	//上传
		2001 => '上传图片出错',
		2002 => '上传视频出错',

		//商品
		3001 => "商品尚未上架\n暂时不能做此操作", //controller_cart /cart/addcart
		3002 => '商品不存在或商品id错误', // /shop/createitemlisthtml
    	3003 => '消费金额必须是数字',

		//店铺
		5001 => '用户已经拥有店铺！',
		5002 => '店铺名已经存在',
		5003 => '不通过理由未填写！',
	);

    $code = (int)$code;
    if( !isset($stat[$code])){
        $code = 4004;
    }
    return array(
        'code' => $code,
        'msg' => $stat[$code],
    );
}//END func sysStatus

//载入全局函数
require_once 'function.php';

