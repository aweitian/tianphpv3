<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base\input;

class text extends \tian\ui\base\formInput {
	public function __construct($name = "", $value = "") {
		$this->selfclose = true;
		$this->tagName = "input";
		$this->attributes = array (
				"type" => "text",
				"value" => $value 
		);
		if ($name) {
			$this->setName ( $name );
		}
	}
	public function setValue($val) {
		$this->setAttr ( "value", $val );
		return $this;
	}
	public function setRequire() {
		$this->setAttr ( "require" );
		return $this;
	}
	public function rmRequire() {
		$this->rmAttr ( "require" );
		return $this;
	}
	public function setPlaceholder($placeholder) {
		$this->setAttr ( "placeholder", $placeholder );
		return $this;
	}
	public function rmPlaceholder() {
		$this->rmAttr ( "placeholder" );
		return $this;
	}
}