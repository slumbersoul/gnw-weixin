<?php
/**
 * 类 PDO 接口 
 *
 * @filesource _pdo.class.php
 * @package peck
 * @subpackage _db
 * @version $id: 0.1, utf8, Mon Jan 11 20:52:01 CST 2010
 * @author LD King <kldscs[at]gmail.com>
 * @copyright Copyleft (D.) 2007 - 2010 MiFunny China Inc.
 * @link http://mifunny.info/
 * @example 
 *   
 */
class Zpdo_Natural extends PDO{
	//* @var int $query_count 本次连接所执行的SQL语句数目
    public $query_count = 0;

    //最后一个sql
    public $last_query;

    //记录Database的dsn
    protected $dsn;


    public function  __construct(array $policy=null){
        if( empty($policy['dsn']) ){
            throw new LogicException('('.__METHOD__.')'.'Error: database DSN information required!');
        }else{
            try {
                parent::__construct($policy['dsn'],$policy['username'],$policy['password'],$policy['options']);
                //parent::__construct($policy['dsn'],$policy['username'],$policy['password']);
                
				$this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC); //setFetchMode fetch模式,关联数组
                $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $this->dsn = $policy['dsn'];

            }catch(PDOException $e){
			    throw new RuntimeException('('.__METHOD__.')'.'Database Server Connect Error: '.$e->__toString());
            }
        }
    }
    
    public function query($statement){
        ++ $this->query_count;
        $this->last_query = $statement;
        $s = microtime(true);
		
        $res = parent::query($statement);
        $e = microtime(true);
        $this->log($statement, $e - $s, 'query');
        return $res;
    }

    public function exec($statement){
        ++ $this->query_count;
        $this->last_query = $statement;
        $s = microtime(true);
		
        $res = parent::exec($statement);
        $e = microtime(true);
        $this->log($statement, $e - $s, 'exec');
        return $res;
    }

    public function prepare($statement, array $driver_options=array()){
        ++ $this->query_count;
        $this->last_query = $statement;
        $res = parent::prepare($statement, $driver_options);
        return $res;
    }


    /**
     * 返回第一行
     * 
     * @param String $statement SQL语句
     * @return array / boolean
     */
    public function get_one($statement, array $param=null){
        $s = microtime(true);
        $stmt = $this->prepare($statement);
        
        if( empty($param)){
            $stmt->execute();
        }else{
            $stmt->execute($param);
        }
        $res = false;
        if( is_object($stmt) ){
        	$res = $stmt->fetch();
        }
        $e = microtime(true);
        $this->log($statement, $e - $s, 'get_one');
        return $res;
    }

    /**
     * 返回全部结果
     * 
     * @param String $statement SQL语句
     * @return array / boolean
     */
    public function get_all($statement, array $param=null){
        $s = microtime(true);
        $stmt = $this->prepare($statement);
        
        if( empty($param)){
            $stmt->execute();
        }else{
            $stmt->execute($param);
        }
        $res = false;
        if( is_object($stmt) ){
        	$res = $stmt->fetchAll();
        }
        $e = microtime(true);
        $this->log($statement, $e - $s, 'get_all');
        return $res;
    }

    /**
     * 使用prepare执行一条语句（update、delete）
     * 
     * @param string $statement SQL语句
     * @param array $param 参数
     * @return boolean
     */
    public function execute($statement, array $param=null){
        $s = microtime(true);
        $stmt = $this->prepare($statement);

        $res = false;
        if( empty($param)){
            $res = $stmt->execute();
        }else{
            $res = $stmt->execute($param);
        }
        $e = microtime(true);
        $this->log($statement, $e - $s, 'execute');
        return $res;
    }
    
    /**
     * 检查 链接是否还存在
     * @return boolean
     */
    public function ping(){
        try {
            $this->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
			return false;
        }
    }


    //写SQLstat日志
    public function log($statement, $time = 0, $action = ''){
        //sqlstat_log($statement, $time, $action, $this->dsn);
    }//END func log


}//END pdo 


