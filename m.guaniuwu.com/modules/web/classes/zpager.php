<?php
/**
 * peck 分页器, 
 *   返回分页 类似: '0,10'; 返回页码: 类似 array(1=>false,2=>true,3=>false), true 为当前页;
 *
 * @filesource _pager.class.php
 * @package peck
 * @subpackage _helper
 * @version $id: 0.1, utf8, Sat Jan  9 10:30:11 CST 2010
 * @author SlumberSoul
 * @copyright Copyleft (D.) 2007 - 2010 MiFunny China Inc.
 * @link http://mifunny.info/
 * @see  
 */
class Zpager{

    /**
     * 分页配置, 参考 PEAR::Pager
     *
     * @var array
     *   totalItems [integer] : 需要分页的记录总数;
     *   perPage [integer]：每页显示多少条数据记录;
     *   expanded [boolean]：
     *      默认值为TRUE，显示分页数的计算方式为2*delta+1,
     *      当将这个值设定为false时，Jumping和Sliding两种分页模式的delta值代表的是不同的含义;
     *   delta [integer]：显示多少分页,
     *     Jumping模式，delta参数设定值代表一次显示多少分页数,
     *     Sliding模式, 在当前分页之前和之后显示多少个分页数;
     *   mode [string]：设定分页的模式，即'Jumping' 或者'Sliding';
     *   currentPage [integer]：当前页;
     *   clearIfVoid [boolean]：如果只有一页，不显示分页的连接，返回一个NULL，默认值为FALSE;
     *
     *   urlOptions [array]：链接组合参数;
     */
    protected $_opts = array(
        'totalItems' => null,
        'perPage' => 10,
        'expanded' => true,
        'delta' =>  3,
        'mode' => 'Sliding',
        'currentPage' => 1,
        'clearIfVoid' => false,

        // 接下来 是结果集
        'offset' => 0,
        'limit' => null, //数据库分页, like '0,10'
        'firstPage' => 1, //第一页
        'lastPage' => null, //最后一页
        'prevPage' => null, //上一页, 如果等于 firstPage, 为NULL; 
        'nextPage' => null, //下一页, 如果等于 endPage, 为NULL;
        'totalPages' => null, //总页数
        'pages' => null, //当前分页列表

        'urlOptions' => array(),
    );

    // 是否需要重建数据
    protected $is_build = false;

    /**
     * 初始化分页
     * 
     * @param int $totalItems 纪录总数
     * @param string $mode 分页模式
     *   Sliding模式向前滑动的, Like Google;
     *   Jumping模式跳跃式前进;
     */
    public function __construct(array $policy=null){
        if( !empty($policy) )  
            $this->_opts = array_merge($this->_opts, $policy);

        $this->build();
    }

    /**
     * 设置配置
     */
    public function set($key, $value){
        if( array_key_exists($key, $this->_opts) ){
            $this->policy[$key] = $value;
            $this->is_build = false;
            return true;
        }else {
            throw new RuntimeException('('.__METHOD__.')Error: have no key name: '.$key);
            return false;
        }
    }

    public function __set($key, $value){
        return $this->set($key, $value);
    }
    public function __get($key){
        return $this->get($key);
    }
    /**
     * 得到配置
     */
    public function get($key){
        if( array_key_exists($key, $this->_opts) ){
            if( !$this->is_build )  $this->build();

            return $this->_opts[$key];
        }else{
            throw new RuntimeException('('.__METHOD__.')Error: have no key names: '.$key);
            return false;
        }
    }

    /**
     * 重建数据
     *
     */
    public function build(){
        extract($this->_opts);
        //总纪录
        if( !is_numeric($totalItems) OR $totalItems<0 ){
            throw new RuntimeException('('.__METHOD__.')Error: totalItems must be an integer !');
            $totalItems = 0;
        }

        $currentPage = intval($currentPage);
        //当前页
        if($currentPage<0) $this->_opts['currentPage'] = $currentPage = 1;
        //总页数, 最后一页
        $this->_opts['totalPages'] = $this->_opts['lastPage'] = $lastPage = ceil($totalItems/$perPage);
        //上一页
        $this->_opts['prevPage'] = ($currentPage==1) ? null : ($currentPage-1);
        //下一页
        $this->_opts['nextPage'] = ($currentPage==$lastPage) ? null : ($currentPage+1);

        //数据分页
        $this->_opts['offset'] = ($currentPage-1)*$perPage;
        $this->_opts['limit'] = $this->_opts['offset'] . ',' . $perPage;

        //选择模式
        if($totalItems<1 or ($lastPage<2 and $clearIfVoid==true) ){
            $this->_opts['pages'] = null;
        }elseif($mode == 'Jumping'){
            $this->_Jumping($currentPage, $delta, $lastPage);
        }else{ // $mode == 'Sliding'
            if($expanded==true){
                $left = $right = $delta; 
            }else{
                if($dalta%2 == 1){
                    $left = $right = floor($delta/2);
                }else{
                    $right = $delta/2;
                    $left = $right-1;
                }
            }//echo 'left: '.$left, 'right: ',$right;
            $this->_Sliding($currentPage, $left, $right, $lastPage); 
        }

        $this->is_build = true;
    }

    /**
     * Jumping模式
     *
     * @param int $start 开始页面;
     * @param int $delta 跨越宽度;
     * @param int $total 总数
     */
    private function _Jumping($start, $delta, $total){
        $end = $start+$delta-1;
        $end = ($total < $end) ? $total : $end;
        $this->_opts['pages'][$start] = true;
        for($i=$start+1; $i<=$end; $i++){
            $this->_opts['pages'][$i] = false;
        }
    }

    /**
     * Sliding模式
     *
     * @param int $current 中间位子
     * @param int $left 左边多少个
     * @param int $right 右边多少个
     * @param int $total 总数
     *   跨度$delta = $left+1+$right
     */
    private function _Sliding($current, $left, $right, $total){
        $delta = $left+1+$right;
        
        if( $total<$delta ){
            $start = 1;
            $end = $total;
        }elseif( $current<($left+1) ){
            $start = 1;
            $end = $delta;
        }elseif( ($current+$right)>=$total ){
            $end=$total;
            $pig = $total-$current+1;
            $start = $current-($delta-$pig);
            
            //$pig = $total%$delta;
            //$start = ($pig>0) ? ($total-$pig+1) : ($total-$delta);
            if($start < 1) $start=1;
        }else{
            $start = $current-$left;
            $end = $current+$right;
        }
        
        for($i=$start; $i<=$end; $i++){
            if($i == $current){
                $this->_opts['pages'][$i] = true;
            }else
                $this->_opts['pages'][$i] = false;
        }//END
    }//END func _Sliding

}//END class _pager
