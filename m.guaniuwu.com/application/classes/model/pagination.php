<?php

class Model_Pagination{

    protected static $_instance;

    public static function instance(){
        if(self::$_instance == null){
            self::$_instance = new self;
        }
        return self::$_instance;
    }	

	public function html_pagination($total = 0,$perpage = 6, $page = 1, array $url_options){

        $pagination = new Zpager( array(
            'totalItems' => $total,
            'perPage' => $perpage,
            'delta' =>  2,  //当前页左右各2页
            'mode' => 'Sliding',
            'currentPage' => $page,

            //分页链接参数
            'urlOptions' => $url_options, 
        ));
		$html_pagination = View::factory('template/pagination', array('pagination'=>$pagination))->render(); 
        return empty($html_pagination) ? '' :  $html_pagination ;
    }//END func html_pagination


}
