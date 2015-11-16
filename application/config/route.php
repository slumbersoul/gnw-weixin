<?php
/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

Route::set('user', '<action>', array('action' => '(login|logout|register|forgetpassword)'))
	->defaults(array(
		'controller' => 'user',
	));
Route::set('user_resetpassword', 'user/resetpassword/resethash/<resethash>')
	->defaults(array(
		'controller' => 'user',
		'action'     => 'resetpassword',
		'resethash'  =>  null,
	));	

Route::set('home', 'me(/<action>(/<page>))',array('page'=>'\d+'))
	->defaults(array(
		'controller' => 'home',
		'action'     => 'index',
	));
//trade
Route::set('trade', 'trade(/<controller>(/<action>))',array('controller'=>'cart|order|address|pay'))
	->defaults(array(
		'action'     => 'index',
	));



//验证码
Route::set('captcha_index', 'captcha(/<rnd>)',array('rnd'=>'.*'))
	->defaults(array(
		'controller' => 'captcha',
        'action'     => 'index',
        'rnd'        => null,
    ));

Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'index',
		'action'     => 'index',
	));
