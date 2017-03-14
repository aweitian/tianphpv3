<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月8日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui;

class table {
	/**
	 * 数据的KEY => alias
	 * 表单字段的先后顺序为此为准
	 *
	 * @var array
	 */
	protected $alias = array ();
	/**
	 * CALLBACK有5个参数,
	 * 第一个参数是WRAP的实例，
	 * 第二个是TR ELEMENT，
	 * 第二个是TD ELEMENT，
	 * 第四个是数组一行的内容，
	 * 第五个是单元格式内容
	 *
	 * @var array cof callback
	 */
	public $dataFilter = array ();
	
	/**
	 *
	 * @var array
	 */
	public $data;
	
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $table;
	public $domain = array ();
	public function __construct($data = NULL) {
		if (is_array ( $data )) {
			$this->data = $data;
		}
	}
	/**
	 *
	 * @return \tian\ui\table
	 */
	public function initDefAlias() {
		if (isset ( $this->data [0] )) {
			$this->alias = array ();
			foreach ( $this->data [0] as $key => $v ) {
				$this->alias [$key] = $key;
			}
		}
		return $this;
	}
	/**
	 *
	 * @param array $domain        	
	 * @return \tian\ui\table
	 */
	public function setDomain(array $domain) {
		$this->domain = $domain;
		return $this;
	}
	
	/**
	 * CALLBACK有5个参数,
	 * 第一个参数是WRAP的实例，
	 * 第二个是TR ELEMENT，
	 * 第二个是TD ELEMENT，
	 * 第四个是数组一行的内容，
	 * 第五个是单元格式内容
	 *
	 * @param array $filter        	
	 * @return \tian\ui\table
	 */
	public function setDataFilter(array $filter) {
		$this->dataFilter = $filter;
		return $this;
	}
	/**
	 *
	 * @param string $name        	
	 * @param string $alias        	
	 * @return \tian\ui\table
	 */
	public function setAlias($name, $alias) {
		$this->alias [$name] = $alias;
		return $this;
	}
	/**
	 *
	 * @param array $alias        	
	 * @return \tian\ui\table
	 */
	public function mergeAlias(array $alias) {
		$this->alias = array_merge ( $this->alias, $alias );
		return $this;
	}
	/**
	 *
	 * @param string $formName        	
	 * @return \tian\ui\table
	 */
	public function rmAlias($formName) {
		if (isset ( $this->alias [$formName] )) {
			unset ( $this->alias [$formName] );
		}
		return $this;
	}
	/**
	 *
	 * @param array $data        	
	 * @return \tian\ui\table
	 */
	public function setData(array $data) {
		$this->data = $data;
		return $this;
	}
	/**
	 *
	 * @return array:
	 */
	public function alias() {
		return $this->alias;
	}
	/**
	 *
	 * @param \tian\ui\tableWrap\tableWrap $wrap        	
	 * @return \tian\ui\table
	 */
	public function wrapTable(\tian\ui\tableWrap\tableWrap $wrap) {
		$wrap->wrap ( $this );
		return $this;
	}
}