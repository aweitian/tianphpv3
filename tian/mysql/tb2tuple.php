<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月5日
 * @Desc: 
 * 依赖:
 */
namespace tian\mysql;

class tb2tuple {
	/**
	 *
	 * @var \PDO
	 */
	public $connection;
	public $tbname;
	
	/**
	 *
	 * @var \tian\data\tuple
	 */
	public $tuple;
	private $alias = array ();
	/**
	 *
	 * @var array 元素为要保留的字段名
	 */
	public $mask = null;
	public $skipAutoincrement = false;
	/**
	 * 为真，返回的NAME格式为 表名.字段名
	 * 为假，返回的NAME格式为 字段名
	 *
	 * @var bool
	 */
	public $namespaceName = false;
	/**
	 *
	 * @param \PDO $connection        	
	 * @param bool $namespaceName        	
	 */
	public function __construct(\PDO $connection, $namespaceName = false) {
		$this->connection = $connection;
		$this->tuple = new \tian\data\tuple ();
		$this->namespaceName = $namespaceName;
	}
	/**
	 *
	 * @param string $tbname        	
	 * @param bool $namespace        	
	 * @return \tian\mysql\tb2tuple
	 */
	public function setTbName($tbname) {
		$this->tbname = $tbname;
		return $this;
	}
	/**
	 * 统一起见，数组的KEY为namespaceName成员有关
	 *
	 * @param array $alias        	
	 * @return \tian\mysql\tb2tuple
	 */
	public function setAlias(array $alias) {
		$this->alias = $alias;
		return $this;
	}
	/**
	 * 统一起见，数组的KEY和namespaceName成员有关
	 *
	 * @param array $mask        	
	 * @return \tian\mysql\tb2tuple
	 */
	public function setMask(array $mask) {
		$this->mask = $mask;
		return $this;
	}
	/**
	 *
	 * @param bool $flag        	
	 * @return \tian\mysql\tb2tuple
	 */
	public function setSkipAutoIncrement($flag) {
		$this->skipAutoincrement = ! ! $flag;
		return $this;
	}
	/**
	 *
	 * @return \tian\rirResult
	 */
	public function initTuple() {
		if (! is_string ( $this->tbname ))
			return new \tian\rirResult ( 1, _ ( "缺少表名" ) );
		return $this->initFields ();
	}
	private function initFields() {
		$sth = $this->connection->prepare ( "SHOW FULL COLUMNS FROM `$this->tbname`" );
		$ret = $sth->execute ();
		if (! $ret) {
			$info = $sth->errorInfo ();
			return new rirResult ( 2, $info [2] );
		}
		$res = $sth->fetchAll ( \PDO::FETCH_ASSOC );
		
		// var_dump($this->mask);
		
		// | Field | Type | Collation | Null | Key | Default | Extra | Privileges | Comment |
		foreach ( $res as $v ) {
			// name,alias,dataType,domain,default,comment
			// ,isUnsiged,allowNull,isPk,isAutoIncrement
			if ($this->namespaceName) {
				$fieldname = $this->tbname . "." . $v ["Field"];
			} else {
				$fieldname = $v ["Field"];
			}
			
			if (is_array ( $this->mask ) && ! in_array ( $fieldname, $this->mask ))
				continue;
			if ($this->skipAutoincrement === true && $v ["Extra"] === 'auto_increment')
				continue;
			$tlu = $this->_parseType ( $v ["Type"] );
			$data = array (
					"name" => $fieldname,
					"alias" => (array_key_exists ( $fieldname, $this->alias )) ? ($this->alias [$fieldname]) : ($v ["Comment"] ? $v ["Comment"] : $fieldname),
					"dataType" => $tlu ["type"],
					"domain" => $tlu ["len"],
					"default" => $v ["Default"],
					"comment" => $v ["Comment"],
					"allowNull" => $v ["Null"] === "YES",
					"isUnsiged" => $tlu ["unsiged"],
					"isPk" => $v ["Key"] == "PRI",
					"isAutoIncrement" => $v ["Extra"] === 'auto_increment' 
			);
			$field = new \tian\mysql\field ( $data );
			$this->tuple->append ( $field );
		}
		return new \tian\rirResult ();
	}
	private function _parseType($t) {
		if (preg_match ( "/^[a-z]+$/", $t )) {
			return array (
					'type' => $t,
					'len' => null,
					'unsiged' => null 
			);
		} else if (preg_match ( "/^(set|enum)\\(((?:'\\w+',)*'\w+')\\)$/", $t, $matches )) {
			$arr = explode ( ",", str_replace ( "'", "", $matches [2] ) );
			return array (
					'type' => $matches [1],
					'len' => array_combine ( $arr, $arr ),
					'unsiged' => null 
			);
		} else if (preg_match ( "/^([a-z]+)\(([0-9]+)\)( unsigned)?$/", $t, $matches )) {
			return array (
					'type' => $matches [1],
					'len' => $matches [2],
					'unsiged' => (isset ( $matches [3] )) ? true : false 
			);
		} else {
			return array (
					'type' => null,
					'len' => null,
					'unsiged' => null 
			);
		}
	}
}