<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base\input;

class select extends \tian\ui\base\formInput {
	public $domain = array ();
	
	/**
	 *
	 * @param unknown $name        	
	 * @param unknown $domain        	
	 * @param unknown $def        	
	 * @param string $muti        	
	 */
	public function __construct($name, $domain = array(), $def = array(), $muti = false) {
		foreach ( $domain as $dk => $dv ) {
			$r = new option ( $dv, $dk );
			if ($muti) {
				$this->setMuti ();
				if (is_array ( $def ) && in_array ( $dk, $def )) {
					$r->setSelected ();
				}
			} else {
				if (is_string ( $def ) && $def == $dk) {
					$r->setSelected ();
				}
			}
			$this->appendNode ( $r );
		}
		$this->domain = $domain;
		$this->tagName = "select";
		$this->setName ( $name );
	}
	public function setMuti() {
		$this->setAttr ( "multiple", "multiple" );
		return $this;
	}
	public function setSize($size) {
		$this->setAttr ( "size", $size );
		return $this;
	}
	
	/**
	 * 传递参数两个参数
	 * item,类型为\tian\ui\base\option
	 * index 孩子中顺序
	 *
	 * @param callback $callback        	
	 * @return \tian\ui\base\input\select
	 */
	public function map($callback) {
		if (is_callable ( $callback )) {
			$i = 0;
			foreach ( $this->childNodes as $item ) {
				call_user_func_array ( $callback, array (
						"index" => $i,
						"item" => $item 
				) );
				$i ++;
			}
		}
		return $this;
	}
}
class option extends \tian\ui\base\element {
	/**
	 *
	 * @var \tian\ui\base\textnode;
	 */
	public $textNode;
	
	/**
	 *
	 * @param string $name        	
	 * @param string $value        	
	 * @param array $domain
	 *        	数组KEY为ENUM的定义域,VALUE为别名
	 */
	public function __construct($text = "", $value = NULL) {
		$this->selfclose = false;
		$this->tagName = "option";
		$this->attributes = array (
				"value" => $value 
		);
		$this->textNode = new \tian\ui\base\textnode ( $text );
		$this->appendNode ( $this->textNode );
	}
	public function setSelected() {
		$this->setAttr ( "selected" );
		return $this;
	}
	public function rmSelected() {
		$this->rmAttr ( "selected" );
		return $this;
	}
	/**
	 *
	 * @param string $label        	
	 * @return \tian\ui\base\input\option
	 */
	public function setText($text) {
		$this->textNode->setText ( $text );
		return $this;
	}
}
