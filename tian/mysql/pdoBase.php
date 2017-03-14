<?php

/**
 * @author awei.tian
 * date: 2013-8-10
 * 说明:MYSQL PDO 最基本的操作
 * 此为默认模式。 PDO 将只简单地设置错误码，
 * 可使用 PDO::errorCode() 和 PDO::errorInfo() 方法来检查语句和数据库对象。
 * 如果错误是由于对语句对象的调用而产生的，那么可以调用那个对象的 
 * PDOStatement::errorCode() 或 PDOStatement::errorInfo() 方法。
 * 如果错误是由于调用数据库对象而产生的，那么可以在数据库对象上调用上述两个方法
 * 
 */
namespace tian\mysql;

const ERROR_EXCEED_LIMIT_COUNT = 0x1000;
class pdoBase {
	private static $pdo = null;
	/**
	 *
	 * @var \PDO
	 */
	public $connection;
	private $errorInfo; // string
	private $errorCode; // code
	const NONERRCODE = "00000";
	public function __construct(\PDO $connection) {
		$this->connection = $connection;
		$this->connection->setAttribute ( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_SILENT );
	}
	/**
	 *
	 * @return string
	 */
	public function getErrorInfo() {
		return $this->errorInfo;
	}
	public function getErrorCode() {
		return $this->errorCode;
	}
	public function hasError() {
		return $this->getErrorCode () !== self::NONERRCODE;
	}
	public function resetErr() {
		$this->errorCode = self::NONERRCODE;
		$this->errorInfo = "";
	}
	/**
	 *
	 * 返回插入ID
	 *
	 * @param string $sql        	
	 * @param array $data        	
	 * @param array $bindType
	 *        	KEY和DATA一样，值为PDO:PARAM_**
	 * @return int
	 */
	public function insert($sql, $data = array(), $bindType = array()) {
		$this->resetErr ();
		$sth = $this->connection->prepare ( $sql );
		if (! $sth) {
			$this->errorInfo = $this->connection->errorInfo ();
			$this->errorInfo = $this->errorInfo [2];
			$this->errorCode = $this->connection->errorCode ();
			return 0;
		}
		foreach ( $data as $k => $v ) {
			$sth->bindValue ( $k, $v, array_key_exists ( $k, $bindType ) ? $bindType [$k] : \PDO::PARAM_STR );
		}
		if ($sth->execute ()) {
			$id = $this->connection->lastInsertId ();
			return $id;
		} else {
			$this->errorInfo = $sth->errorInfo ();
			$this->errorInfo = $this->errorInfo [2];
			$this->errorCode = $sth->errorCode ();
			return 0;
		}
	}
	/**
	 *
	 * 返回一维数组,SQL中的结果集中的第一个元组
	 *
	 * @param string $sql        	
	 * @param array $data        	
	 * @return array;
	 */
	public function fetch($sql, $data = array(), $bindType = array(), $fetch_mode = \PDO::FETCH_ASSOC) {
		$this->resetErr ();
		$sth = $this->connection->prepare ( $sql );
		if (! $sth) {
			$this->errorInfo = $this->connection->errorInfo ();
			$this->errorInfo = $this->errorInfo [2];
			$this->errorCode = $this->connection->errorCode ();
			return array ();
		}
		foreach ( $data as $k => $v ) {
			$sth->bindValue ( $k, $v, array_key_exists ( $k, $bindType ) ? $bindType [$k] : \PDO::PARAM_STR );
		}
		$sth->setFetchMode ( $fetch_mode );
		if ($sth->execute ()) {
			$ret = $sth->fetch ();
			if (! is_array ( $ret ))
				return array ();
			return $ret;
		}
		$this->errorInfo = $sth->errorInfo ();
		$this->errorInfo = $this->errorInfo [2];
		$this->errorCode = $sth->errorCode ();
		return 0;
	}
	
	/**
	 *
	 * 返回二维数组，最后一个参数默认为500，查询结果超过，切割后返回
	 *
	 * @param string $sql        	
	 * @param array $data        	
	 * @return array;
	 */
	public function fetchAll($sql, $data = array(), $bindType = array(), $fetch_mode = \PDO::FETCH_ASSOC, $maxRows = 500) {
		$this->resetErr ();
		$sth = $this->connection->prepare ( $sql );
		if (! $sth) {
			$this->errorInfo = $this->connection->errorInfo ();
			$this->errorInfo = $this->errorInfo [2];
			$this->errorCode = $this->connection->errorCode ();
			return array ();
		}
		foreach ( $data as $k => $v ) {
			$sth->bindValue ( $k, $v, array_key_exists ( $k, $bindType ) ? $bindType [$k] : \PDO::PARAM_STR );
		}
		$sth->setFetchMode ( $fetch_mode );
		if ($sth->execute ()) {
			$r = $sth->fetchAll ();
			$maxRows = $maxRows > 0 ? $maxRows : 500;
			if (count ( $r ) > $maxRows) {
				return array_slice ( $r, 0, $maxRows );
			}
			return $r;
		}
		$this->errorInfo = $sth->errorInfo ();
		$this->errorInfo = $this->errorInfo [2];
		$this->errorCode = $sth->errorCode ();
		return array ();
	}
	/**
	 *
	 * 返回影响行数
	 *
	 * @param string $sql        	
	 * @param array $data        	
	 * @param array $bindType
	 *        	KEY和DATA一样，值为PDO:PARAM_**
	 * @return int
	 */
	public function exec($sql, $data = array(), $bindType = array()) {
		$this->resetErr ();
		$sth = $this->connection->prepare ( $sql );
		if (! $sth) {
			$this->errorInfo = $this->connection->errorInfo ();
			$this->errorInfo = $this->errorInfo [2];
			$this->errorCode = $this->connection->errorCode ();
			return 0;
		}
		foreach ( $data as $k => $v ) {
			$sth->bindValue ( $k, $v, array_key_exists ( $k, $bindType ) ? $bindType [$k] : \PDO::PARAM_STR );
		}
		if ($sth->execute ()) {
			return $sth->rowCount ();
		} else {
			$this->errorInfo = $sth->errorInfo ();
			$this->errorInfo = $this->errorInfo [2];
			$this->errorCode = $sth->errorCode ();
			return 0;
		}
	}
}
