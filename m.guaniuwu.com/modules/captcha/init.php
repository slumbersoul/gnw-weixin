<?php defined('SYSPATH') or die('No direct script access.');

function captcha_config($group){
	$captcha_config=include('config/captcha.php');
	return $captcha_config[$group];
}


// Catch-all route for Captcha classes to run
// Route::set('captcha', 'captcha(/<group>)')
// 	->defaults(array(
// 		'controller' => 'captcha',
// 		'action' => 'index',
// 		'group' => NULL));
