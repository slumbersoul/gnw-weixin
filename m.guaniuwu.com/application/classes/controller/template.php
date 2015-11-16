<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Template extends Controller {
	/**
	 * @var  View  page template
	 */
	public $template = 'template/template';

	/**
	 * @var  boolean  auto render template
	 **/
	public $auto_render = TRUE;

    public function before()
    {
		if ($this->auto_render === TRUE)
		{
			// Load the template
			$this->template = View::factory($this->template);
		}

		if ($this->auto_render === TRUE) {
            $this->view_prepare();
        }
        
        return parent::before();
	}//END func before

    public function after(){
		if ($this->auto_render === TRUE)
		{
			$this->response->body($this->template->render());
		}

        return parent::after();
    }

    
    /***
     * 为视图做初始化
     */
    public function view_prepare(){
        $controller = $this->request->controller();
        $action     = $this->request->action();
        View::bind_global('controller', $controller);
        View::bind_global('action',     $action);

        // Load the template
        if( !($this->template instanceof View) ){
            $this->template = View::factory($this->template);
        }

        $this->template->title = $GLOBALS['WEB_TITLE'];

		$this->template->scripts = array();       //放在头部的Javascript
        $this->template->scriptscoda = array();   //放在尾部的Javascript
        $this->template->scriptsglobe = array();   //放在尾部的全局Javascript
        $this->template->styles = array();
		
		$this->add_style('reset.css');
		$this->add_style('base.css');
		$this->add_style('globe.css');
		
		//$this->add_script('jquery-1.7.2.min.js','head');
		$this->add_script('jquery-1.11.2.min.js','head');
		$this->add_script('jquery-migrate-1.2.1.min.js','head');
		$this->add_script('base.js','head');
		$this->add_script('globe.js','foot');

        $this->set_keywords($GLOBALS['WEB_KEYWORDS']);
        $this->set_description($GLOBALS['WEB_DESC']);
    }//END func view_prepare

    public function add_style($file) {
        if( ! $this->template instanceof View ){
            return;
        }
		$this->template->styles[] = "/css/{$file}"; 
    }

    /**
     * 添加javascript
     * 
     * @param $file js文件名
     * @param $is_coda true则添加js至尾部
     *
     */
    public function add_script($file, $where='head') {
        if( ! $this->template instanceof View ){
            return;
        }

        $file = "js/".$file;

        if('head' == $where){
            $this->template->scripts[] = $file;
        }else if ('globe' == $where) {
            $this->template->scriptsglobe[]     = $file;
        }else if ('foot' == $where){
            $this->template->scriptscoda[]     = $file;
        }else{
            $this->template->scripts[] = $file;
        }
            
    }//END func add_script

	/***
     * 添加进javascript WEBPROFILE 全局变量
     *
     *   对应 $GLOBALS['WEBPROFILE']
     *
     */
    public function add_profile($key, $val=null){
        $GLOBALS['WEBPROFILE'][$key] = $val;
    }//END func add_profile

    public function set_keywords($keywords) {
        $this->template->keywords= $keywords;
    }

    public function set_description($description) {
        $this->template->description = mb_substr($description,0,110,'UTF-8');
    }
	
	//待修改，forbidden
	public function error403(){
		header('Location:/error/403');	
		exit;
	}
	//待修改，未找到
	public function error404(){
		header('Location:/error/404');	
		exit;
	}

	/**
     * 如果没有验证, 返回错误代码
     */
    public function ajax_user_auth(){ 
        if( ! is_auth() ){
            jsonReturn(1022); //未登录
            exit;
        }
        return true;
    }//END func ajax_user_auth

}//END class
