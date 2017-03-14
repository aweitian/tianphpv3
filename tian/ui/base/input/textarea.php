<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base\input;

class textarea extends \tian\ui\base\formInput {
	/**
	 *
	 * @var \tian\ui\base\textnode;
	 */
	public $textNode;
	public function __construct($name = "", $value = "") {
		$this->tagName = "textarea";
		$this->attributes = array (
				"type" => "text" 
		);
		if ($name) {
			$this->setName ( $name );
		}
		$this->textNode = new \tian\ui\base\textnode ( $value );
		$this->appendNode ( $this->textNode );
	}
	public function setValue($val) {
		$this->textNode->setText ( $val );
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