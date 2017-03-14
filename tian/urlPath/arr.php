<?php

/**
 * @Author: awei.tian
 * @Date: 2016年7月14日
 * @Desc: 把URL的PATH部分按 / 分割成数组
 * 依赖:
 * 	\tian\url
 */
namespace tian\urlPath;

class arr {
	/**
	 *
	 * @var \tian\url
	 */
	public $url;
	private $arr;
	/**
	 * 完整的URL
	 *
	 * @param string $url        	
	 */
	public function __construct($url = "") {
		$this->url = new \tian\url ( $url );
		if ($url == "") {
			$this->arr = array ();
		} else {
			$this->arr = explode ( "/", trim ( $this->url->path, "/" ) );
		}
	}
	
	/**
	 *
	 * @param string $url        	
	 * @return \tian\urlPath\arr
	 */
	public static function getInstance($url) {
		return new self ( $url );
	}
	
	/**
	 * 返回长度
	 *
	 * @return number
	 */
	public function getLength() {
		return count ( $this->arr );
	}
	/**
	 * 长度不在数组长度之内，在后面添加
	 *
	 * @param int $index        	
	 * @param string $pathname        	
	 */
	public function set($index, $pathname) {
		if ($index < $this->getLength ()) {
			$this->arr [$index] = $pathname;
		} else {
			$this->arr [] = $pathname;
		}
	}
	/**
	 * 不存在返回NULL
	 *
	 * @param int $index        	
	 * @return NULL | pathname
	 */
	public function get($index) {
		if ($index < $this->getLength ()) {
			return $this->arr [$index];
		} else {
			return null;
		}
	}
	/**
	 * 返回完整的URL
	 *
	 * @return string
	 */
	public function __toString() {
		$url = join ( "/", $this->arr );
		$this->url->path = $url == "" ? "/" : "/" . $url;
		return $this->url->__toString ();
	}
	/**
	 * 别名__toString()
	 *
	 * @see \tian\urlPath\arr\__toString()
	 * @return string
	 */
	public function getUrl() {
		return $this->__toString ();
	}
}