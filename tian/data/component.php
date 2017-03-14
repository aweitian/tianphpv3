<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: 关系数据库中的分量
 * 依赖:
 */
namespace tian\data;

abstract class component {
	/**
	 * 字段名
	 *
	 * @var string
	 */
	public $name;
	/**
	 * 别名
	 *
	 * @var string
	 */
	public $alias;
	
	/**
	 *
	 * @var string 例如:tinyint,smallint,int,bigint
	 */
	public $dataType;
	/**
	 *
	 * @var 一般在数据类型为集合时使用
	 */
	public $domain;
	public $domainDescription;
	public $default;
	public $comment;
	
	// 根据实际情况添加三个字段
	public $isUnsiged = false;
	public $allowNull = false;
	public $isPk = false;
	public $isAutoIncrement = false;
	/**
	 * 可用的KEY name,alias,dataType,domain,default,comment,isUnsiged,allowNull,isPk,isAutoIncrement
	 *
	 * @param array $data        	
	 */
	public function __construct(array $data) {
		foreach ( $data as $key => $val ) {
			if (property_exists ( $this, $key )) {
				$this->{$key} = $val;
			}
		}
	}
	
	/**
	 *
	 * @param string $value        	
	 * @return bool
	 */
	abstract public function domainChk($value);
	public static function intDomainChk($value) {
		return is_int ( $value );
	}
}