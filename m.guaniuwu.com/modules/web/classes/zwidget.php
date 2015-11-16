<?php
/**
 *  侧栏通用方法;
 *
 * @filesource zwidget.php
 * @package mogujie 
 * @subpackage juan
 * @version $id: 0.1, utf8, Tue Mar 22 20:08:17 CST 2011$
 * @author LD King <kldscs[at]gmail.com>
 * @copyright Copyleft (D.) 2007 - 2010 LD pr.
 */
class Zwidget {

    //各个view片段[string]
    protected $widgets = array();

    //一共有几个widget 
    protected $counter = 0;

	public function __construct(){
	}

    /**
     * 添加一个widget
     *
     */
    public function add($widget, $index=null){
        $index = intval($index);
        $index = ( 0<$index ) ? 10*$index+5 : 10*($this->counter+1);

        $this->widgets[$index] = $widget;
        $this->counter ++;
    }//END func add

    /**
     * 排序输出
     */
    public function __toString(){
        ksort($this->widgets, SORT_NUMERIC);
        return implode('', $this->widgets);
    }//END func __toString 


    ///////////////////////////////
    //// 新的code
    ///////////////////////////////
    //我们的zw = array(
    //  'groupname'[] => array( 'file'   => 'abc',
    //             'params' => array('a'=>1,)
    //)
    protected static $zwidgets = array();

    /**
     * 添加一个widget
     *
     */
    public static function zadd($file, array $params=null, $group='body'){
        self::$zwidgets[$group][] = array('file'=>$file, 'params'=>$params);
    }//END func add

    /**
     * 显示所有的widgets
     *
     */
    public static function zshow($group='body'){
        if(empty(self::$zwidgets[$group])) return;
        //ksort(self::$zwidgets, SORT_NUMERIC);

        //循环输出wg  
        foreach(self::$zwidgets[$group] as &$zg){
            self::zshowSingle($zg['file'], $zg['params']); 
            unset($zg);
        }

    }//END function zshow

    /**
     * 解压显示单个 widget
     *
     * @param string   $file         视图文件
     * @param array    $params       参数
     * @param boolean  $ob_get_clean 直接返回内容,不显示;
     *
     */
    public static function zshowSingle($file, array $params=null, $ob_get_clean=false){
        
        if( true==$ob_get_clean ){
            // Capture the view output
            ob_start();
        } else{
            //ob_implicit_flush(true); //强制浏览器开始解析html
        }

        //$errno = error_reporting();
        //error_reporting($errno & ~E_NOTICE);
        
        $file = APPPATH . 'views/' . $file . '.php';
        if(!empty($params)){
           extract($params, EXTR_OVERWRITE && EXTR_REFS); 
        }
	    
        //为兼容老的View 
        if(!empty(View::$_global_data)){
			extract(View::$_global_data, EXTR_SKIP);
        }

        include($file);
        //error_reporting($errno);

        if( true==$ob_get_clean ){
            // Get the captured output and close the buffer
            return ob_get_clean();
        }

        //在View没有切换之前, 先不flush; 
        //ob_flush();
        //flush();
    }//END func 

    //获取视图解析后的HTML
    public static function render($file, array $params=null){
        return self::zshowSingle($file, $params, true);
    } 



}//END class Zwidget

