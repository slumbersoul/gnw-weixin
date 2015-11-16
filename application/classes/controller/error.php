<?php
class Controller_Error extends Controller_Template {
    
    public function before()
	{
		$return = parent::before();
		//
		//if ($this->auto_render === TRUE) {
        //    $this->view_prepare();
        //}

	}//END func before

    
    //404页面
    public function action_guest(){
		$msg = "你好，你的账号异常，为保护你的账号安全，暂时无法登陆。请联系工作人员";
        return $this->error404($msg);    
    }//END action_404
    
    
    //特殊错误[门卫拦住了!]
    public function action_index(){
		$msg = "有点小抽抽，喵星人正在抢修中，一下就好哟！";
        return $this->error404($msg);    
    }//END action_error 
	
	public function action_403(){
		$this->add_style('page-error.css');
		Zwidget::zadd('error/403', array(), 'content');
       	return;	
	}
		
	public function action_404(){
		$this->add_style('page-error.css');
		Zwidget::zadd('error/404', array(), 'content');
       	return;	
	}
}//END class
