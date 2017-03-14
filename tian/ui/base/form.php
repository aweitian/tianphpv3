<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月5日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base;

class form extends \tian\ui\base\element {
	public $method = "get";
	public $action = "";
	public $isUpload = false;
	public function __construct() {
		$this->tagName = "form";
	}
	
	/**
	 *
	 * @return \tian\ui\base\form
	 */
	public function setUploadForm() {
		$this->setAttr ( "enctype", "multipart/form-data" );
		return $this;
	}
	/**
	 * _blank,_self,_parent,_top,framename
	 *
	 * @param string $tar        	
	 * @return \tian\ui\base\form
	 */
	public function setTarget($tar = "_blank") {
		$this->setAttr ( "target", $tar );
		return $this;
	}
	
	/**
	 * application/x-www-form-urlencoded,multipart/form-data,text/plain
	 *
	 * @param string $enc        	
	 * @return \tian\ui\base\form
	 */
	public function setEnctype($enc = "application/x-www-form-urlencoded") {
		$this->setAttr ( "enctype", $enc );
		return $this;
	}
	/**
	 * UTF-8,ISO-8859-1,gb2312
	 *
	 * @param string $cs        	
	 * @return \tian\ui\base\form
	 */
	public function setCharset($cs = "UTF-8") {
		$this->setAttr ( "accept-charset", $cs );
		return $this;
	}
	/**
	 *
	 * @param string $cs        	
	 * @return \tian\ui\base\form
	 */
	public function setAction($act) {
		$this->setAttr ( "action", $act );
		return $this;
	}
	/**
	 *
	 * @param string $m        	
	 * @return \tian\ui\base\form
	 */
	public function setMethod($m) {
		$this->setAttr ( "method", $m );
		return $this;
	}
}