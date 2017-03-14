<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: Tuple 元组 关系表中的一行称为一个元组
 * 依赖:
 */
namespace tian\data;

class tuple implements \IteratorAggregate {
	private $children = array ();
	public function __construct(array $data = array()) {
		foreach ( $data as $t ) {
			if ($t instanceof \tian\data\component) {
				$this->children [] = $t;
			}
		}
	}
	/**
	 * (non-PHPdoc)
	 *
	 * @see IteratorAggregate::getIterator()
	 */
	public function getIterator() {
		return new \ArrayIterator ( $this->children );
	}
	/**
	 *
	 * @param \tian\data\tuple $tuple        	
	 * @return \tian\data\tuple
	 */
	public function appendTuple(\tian\data\tuple $tuple) {
		foreach ( $tuple as $t ) {
			$this->append ( $t );
		}
		return $this;
	}
	/**
	 *
	 * @param \tian\data\tuple $tuple        	
	 * @return \tian\data\tuple
	 */
	public function prependTuple(\tian\data\tuple $tuple) {
		foreach ( $tuple as $t ) {
			$this->prepend ( $t );
		}
		return $this;
	}
	/**
	 *
	 * @param int $pos        	
	 * @param \tian\data\tuple $tuple        	
	 * @return \tian\data\tuple
	 */
	public function insertTuple($pos, \tian\data\tuple $tuple) {
		$i = $pos;
		foreach ( $tuple as $t ) {
			$this->insert ( $i ++, $t );
		}
		return $this;
	}
	
	/**
	 *
	 * @param int $pos
	 *        	可正可负
	 * @param \tian\data\component $component        	
	 * @return \tian\data\tuple
	 */
	public function insert($pos, \tian\data\component $component) {
		if ($pos === 0) {
			array_unshift ( $this->children, $component );
		} else if ($pos === count ( $this->children )) {
			$this->children [] = $component;
		} else {
			$arr = array_splice ( $this->children, 0, $pos );
			$arr [] = $component;
			$this->children = array_merge ( $arr, $this->children );
		}
		return $this;
	}
	
	/**
	 *
	 * @param \tian\data\component $component        	
	 * @return \tian\data\tuple
	 */
	public function append(\tian\data\component $component) {
		$this->children [] = $component;
		return $this;
	}
	
	/**
	 *
	 * @param \tian\data\component $component        	
	 * @return \tian\data\tuple
	 */
	public function prepend(\tian\data\component $component) {
		array_unshift ( $this->children, $component );
		return $this;
	}
	/**
	 * 位置必须正确，否则删除失败
	 * @param int $pos        	
	 * @return \tian\data\tuple
	 */
	public function remove($pos) {
		if ($pos >= count ( $this->children ) || $pos < 0) {
			return $this;
		}
		if (isset ( $this->children [$pos] )) {
			unset ( $this->children [$pos] );
		}
		return $this;
	}
}