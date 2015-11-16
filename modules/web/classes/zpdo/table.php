<?php
/**
 * PDO 单表操作
 * 
 */
class Zpdo_Table {
    const INSERT_DEFAULT    =   0;
    const INSERT_INGORE     =   1;
    const INSERT_DELAYED    =   2;

	/**
	 * 数据库对象
	 * @var $_db PDO
	 */
	protected $_db;
	
	/**
	 * 表名
	 * @var string $_table_name 
	 */
	protected $_name;


    /**
     * 确定表明， 使用哪个数据库
     *
     * @param string $table
     * @param string $dbname
     *
     */
    public function __construct( $table=null, $dbname=null){
        $this->_db = Zpdo::instance($dbname);
        if( !empty($table) ){
            $this->_name = $table;
        } 
    }

	/**
	 * 获得 数据库 对象
	 * @return object
	 */
	public function getDatabase( $dbname=null ){
		if( !is_object($this->_db) ){
			$this->_db = Zpdo::instance( $dbname );
		}
		return $this->_db;
	}
	
	/**
	 * 更新表名
	 * @param String $new
	 */
	public function changeTableName($new){
		$this->_name = $new;
	}
	
	/**
	 * 插入一整条记录
	 * @param array $arr
	 */
	public function insert(array $arr){
		$db = & $this->_db;
		
		$keys = array_keys($arr);
		$values = array_values($arr);
		$sql = 'INSERT INTO `'.$this->_name.'` ' 
			.' ( `'.implode('`,`', $keys).'` )'
			.' VALUES ( ';
        $params = array();
        foreach($values as &$v){
            if( is_null($v) ){
                $v = 'NULL';
            }elseif( $this->is_float($v) ){
                continue;
            }else{
                $params[] = $v;
                $v = '?';
            }
		}
		$sql .= implode(',', $values).' )';
		if($db->execute($sql, $params) ){
			return $db->lastInsertId();
		}else
			return false;
    }

    /**
     * 批量插入
     */
    public function inserts(array $list, $PRIORITY=self::INSERT_DEFAULT){
        $keys = current($list);
        foreach($keys as $k=>$v){
            $keys[$k] = "`{$k}`";
        }
        $sql = 'INSERT ';
        if( self::INSERT_INGORE==$PRIORITY ){
            $sql .= 'IGNORE ';
        }
        elseif( self::INSERT_DELAYED==$PRIORITY ){
            $sql .= 'DELAYED ';
        }
        $sql .= 'INTO `'.$this->_name.'` ('.implode(',', $keys).') VALUES ';

        foreach($list as $z=>$row){
            foreach($row as $k=>$v){
                $row[$k] = $this->iquote($v);    
            } 
            $list[$z] = '('.implode(',',$row).')';            
        }
        $sql .= implode(',', $list);
        return $this->_db->exec($sql);
    }//END func inserts

	/**
	 * replace 一整条记录
	 * @param array $arr
	 */
	public function replace(array $arr){
		$keys = array_keys($arr);
		$values = array_values($arr);
		$sql = 'REPLACE INTO `'.$this->_name.'` '
			.' ( `'.implode('`,`', $keys).'` )'
			.' VALUES ( ';
		foreach($values as $k=>$v){
			$values[$k] = $this->iquote($v);
		}
        $sql .= implode(',', $values).' )';

		return $this->_db->exec($sql);
    }

	/**
     * Updates table rows with specified data based on a WHERE clause.
     *
     * @param  mixed  $bind  Column-value pairs.
     *                仅支持$key=$value;  
     * @param  mixed  $where UPDATE WHERE clause(s).
     *                默认$key=$value, 同时支持 $key IN array $value; 
     *
     * @return int  The number of affected rows.
     */
	public function update($bind, $where=null){
		$sql = 'UPDATE `'.$this->_name.'` SET ';
        $sql .= $this->_bind($bind) . $this->_where($where);
        
        return $this->_db->exec($sql);
	}

	/**
     * Deletes table rows based on a WHERE clause.
     *
     * @param  mixed   $where DELETE WHERE clause(s).
     *                默认$key=$value, 同时支持 $key IN array $value;
     *
     * @return int  The number of affected rows.
     */
	public function delete($where=null){
		$sql = 'DELETE FROM `'.$this->_name.'` ';
        $sql .= $this->_where($where);
        
        return $this->_db->exec($sql);
	}

   /**
     * Fetches all rows.
     *
     * @param mixed where 
     *                默认$key=$value, 同时支持 $key IN array $value; 
     * @param string|array $order  OPTIONAL An SQL ORDER clause.
     * @param int          $count  OPTIONAL An SQL LIMIT count.
     * @param int          $offset OPTIONAL An SQL LIMIT offset.
     * @return array
     */
	public function fetchAll($where = null, $order = null, $limit = null, $columns = '*'){
		if(is_array($columns)) {
			foreach($columns as $key => $column) {
				$columns[$key] = '`' . $column . '`';
			}
			$column_sql = implode(",", $columns);
		}else {
			$column_sql = '*';
		}
		$sql = 'SELECT '. $column_sql .' FROM `'.$this->_name.'` ';

        $sql .= $this->_where($where) . $this->_order($order);
        
        if( !empty($limit) ){
			$sql .= ' LIMIT '.$limit;
		}
        
        $stmt = $this->_db->query($sql);
		if( is_object($stmt) ){
			return $stmt->fetchAll();
		}else
			return false;
	}

    /**
     * Fetches one row in an object 
     *
     * @param string|array $where  OPTIONAL An SQL WHERE .
     * @param string|array $order  OPTIONAL An SQL ORDER clause.
     * @return array
     */
	public function fetchRow($where = null, $order = null){
		$sql = 'SELECT * FROM `'.$this->_name.'` ';
        $sql .= $this->_where($where) . $this->_order($order);
		$sql .= ' LIMIT 1';

		$stmt = $this->_db->query($sql);
		if( is_object($stmt) ){
			return $stmt->fetch();
		}else
			return false;
	}
	
	/**
	 * 创建数据表
	 * @param string $name 表名
	 */
	public function create($name=null){
	} 
	
	/**
	 * 是否存在表
	 */
	public function ifExists($table=null, $database=null){
		$table = empty($table) ? $this->_name : $table;
		
		$db = & $this->_db;
		
		$sql = 'SELECT * FROM  `information_schema`.`TABLES` WHERE ';
        if( !empty($database) ){ 
            $sql .= ' `TABLE_SCHEMA` = '.$db->quote($database). ' AND ';
        }
		$sql .= ' `TABLE_NAME` =  '.$db->quote($table).' LIMIT 1';
		if( $db->get_one($sql) ){
			return true;
		}else
			return false;
    }

    /**
     * 字段累加
     *
     * @param $update = array('key'=> $offset, )
     * @param $where
     */
    public function increment($update, $where, $offset=1){
        $sql = "UPDATE `{$this->_name}` SET "
            .$this->_update($update, $offset, '+')
            .$this->_where($where);
        return $this->_db->exec($sql);
    }

    /**
     * 字段递减, 安全的方法
     */
    public function decrement($update, $where, $offset=1){
        $sql = "UPDATE `{$this->_name}` SET "
            .$this->_updateForDecrement($update, $offset)
            .$this->_where($where);
        
        return $this->_db->exec($sql);
    }

///////////////////////////////////////////////////////////////////////
    /**
     * 组合clo对应的字段
     *  默认$key=$value, 同时支持 $key IN array $value;  
     */
    protected function _bind($bind=null, $delimiter=','){
        $sql = '';
        if( !empty($bind) ){
            if( is_array($bind)  ){
                foreach($bind as $k=>$v){
                    $pos = stripos($k, ' in');
                    if( 0<$pos ){
                        $k = substr($k, 0, $pos);
                        if( !is_array($v) ){
                            $v = array($v);
                        }
                        $str = ' `'.$k.'` IN (';
                        foreach($v as $c=>$o){
                            $v[$c] = $this->zquote($o, $k);
                        }
                        $str .= implode(',', $v).')';

                    }else{
                        $pos = stripos($k, ' =');
                        if( 0<$pos ) $k=substr($k, 0, $pos);
                        
                        $str = ' `'.$k.'` = '.$this->zquote($v,$k);
                    }
                    $tmp[] = $str;
		        }
		        $sql .= implode($delimiter, $tmp);
            }else{
                $sql .= $bind;
            }
		}
        return $sql; 
    }

    protected function _where($where=null){
        $sql = $this->_bind($where, ' AND ');
        if( !empty($sql) ){
            $sql = ' WHERE '.$sql;
        }
        return $sql;
    }

    /**
     * 字段排序
     * @param string|array $order
     */
    protected function _order($order=null){
        $sql = '';
        if( !empty($order) ){
			$sql .= ' ORDER BY ';
            if( is_array($order) ){
                $keys = array_keys($order);
                $values = array_values($order);
                if( is_string($keys[0]) ){
                    foreach($order as $k=>$v){
                        $tmp[] = ' `'.$k.'` '.$v;
                    }
                   $sql .= implode(',', $tmp); 
                }else{
                    $sql .= implode(',', $values);
                }
            }else{
                $sql .= $order;
            }
		}
        return $sql;
    }

    protected function _updateForDecrement($update, $offset=1){
        if( is_array($update)){
            $usql = array();
            foreach($update as $k=>$v){
                if(is_string($k)){
                    $pos = stripos($k, ' =');
                    if(0<$pos){
                        $k = substr($k, 0, $pos);
                        $usql[] = "`{$k}`=".$this->zquote($v,$k);
                    }else{
                        if((int)$v > 0){
                            $v=(int)$v;
                            $usql[] = "`{$k}`=IF(`{$k}`>={$v},`{$k}`-{$v},`{$k}`)"; 
                        }else{
                            $usql[] = "`{$k}`=IF(`{$k}`>={$offset},`{$k}`-{$offset},`{$k}`)"; 
                        }
                    }//END POS
                }//END string
                else{
                    $usql[] = "`{$v}`=IF(`{$v}`>={$offset},`{$v}`-{$offset},`{$v}`)"; 
                }
            }
            return implode(',', $usql);
        }
        else
            return (false===strpos($update,'=')) ? "$update={$update}-{$offset}" : $update;
    }//END _updateForDecrement

    protected function _update($update, $offset=1, $method="+"){
        if( is_array($update)){
            $usql = array();
            foreach($update as $k=>$v){
                if(is_string($k)){
                    $pos = stripos($k, ' =');
                    if(0<$pos){
                        $k = substr($k, 0, $pos);
                        $usql[] = "`{$k}`=".$this->zquote($v,$k);
                    }else{
                        if((int)$v > 0){
                            $usql[] = "`{$k}`=`{$k}`{$method}".(int)$v; 
                        }else{
                            $usql[] = "`{$k}`=`{$k}`{$method}".$offset; 
                        }
                    }
                }else{
                    $usql[] = "`{$v}`=`{$v}`{$method}".$offset; 
                }
            }
            return implode(',', $usql);
        }
        else
            return (false===strpos($update,'=')) ? "$update={$update}{$method}{$offset}" : $update;
    }

//////////////////////////////////////////////////////
 
	/**
	 * PDO::quote
	 * @param String $msg
	 */
	public function quote($msg){
        return $this->_db->quote($msg);
	}
	
    //is quote   
    public function iquote($v){
        if( is_null($v) ){
            $str = 'NULL';
        }elseif( $this->is_float($v) ){
            $str = $v;
        }else{
            $str = $this->_db->quote($v);
        }

        return $str;
    }

    //is true float
    public function is_float($f){
        //return ($f == (string)(float)$f);
        return in_array(gettype($f), array('integer', 'double', 'float') );
    }

    public function zquote($v, $k=''){
        if( is_null($v) ){
            $str = 'NULL';
        }
        elseif( substr($k,-2,2)=='Id' && $k != 'alipayId' ){
            $str = is_numeric($v) ? $v : 0;
        }
        elseif( $this->is_float($v) ){
            $str = $v;
        }
        else{
            $str = $this->_db->quote($v);
        }

        return $str;
    }//END func zquote


}//END class Zpdo_Table

