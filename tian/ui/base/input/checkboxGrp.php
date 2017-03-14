<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base\input;

class checkboxGrp extends \tian\ui\base\formInput {
	public $domain;
	/**
	 *
	 * @param string $name        	
	 * @param array $domain        	
	 * @param array $def        	
	 * @param string $tagname        	
	 * @param string $attr
	 *        	如果存在组标签，如果DIV，这是DIV的属性
	 */
	public function __construct($name, $domain = array(), $def = array(), $tagname = "", $attr = array()) {
		$this->tagName = $tagname;
		$this->attributes = $attr;
		foreach ( $domain as $dk => $dv ) {
			$r = new checkbox ( $name, $dk, $domain );
			$this->childNodes [] = $r;
			if (is_array ( $def ) && in_array ( $dk, $def )) {
				$r->setChecked ();
			}
			if (! is_null ( $dv )) {
				$r->setLabel ( $dv );
			} else {
				$r->setLabel ( $dk );
			}
		}
		$this->domain = $domain;
	}
	/**
	 * 参数数组可设置三个参数
	 * label 里面可用两个占位符,:for,:label
	 * element 一个占位符 :element
	 * id 两个占位符,:id,:index
	 *
	 * @param array $tpl
	 *        	@id string 用于替换ID 占位符,:id
	 * @param string $id
	 *        	留空随机产生
	 * @return \tian\ui\base\input\checkboxGrp
	 */
	public function setLabel($tpl = array(), $id = '') {
		$def = array (
				"label" => '<label for=":for">:label</label>',
				"element" => '<span>:element</span>',
				"id" => ':id_:index' 
		);
		if ($id == "") {
			$id = \tian\utils\utility::getRandChar ( 6 );
		}
		$arg = array_merge ( $def, $tpl );
		$this->map ( function ($index, \tian\ui\base\input\checkbox $cb) use($arg, $id) {
			$cb->setId ( strtr ( $arg ["id"], array (
					":id" => $id,
					":index" => $index 
			) ) );
			$cb->wrap ( $arg ["element"], ":element" );
			$cb->label = strtr ( $arg ["label"], array (
					":for" => $cb->getId (),
					":label" => $cb->label 
			) );
		} );
		return $this;
	}
	/**
	 * 传递参数两个参数
	 * item,类型为\tian\ui\base\checkbox
	 * index 孩子中顺序
	 *
	 * @param callback $callback        	
	 * @return \tian\ui\base\input\checkboxGrp
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
class checkbox extends \tian\ui\base\element {
	public $label = "";
	/**
	 *
	 * @param string $name        	
	 * @param string $value        	
	 * @param array $domain
	 *        	数组KEY为ENUM的定义域,VALUE为别名
	 */
	public function __construct($name = "", $value = NULL) {
		$this->selfclose = true;
		$this->tagName = "input";
		$this->attributes = array (
				"type" => "checkbox",
				"value" => $value 
		);
		if ($name) {
			$this->setName ( $name );
		}
	}
	/**
	 *
	 * @return \tian\ui\base\input\checkbox
	 */
	public function setChecked() {
		$this->setAttr ( "checked" );
		return $this;
	}
	/**
	 *
	 * @return \tian\ui\base\input\checkbox
	 */
	public function rmChecked() {
		$this->rmAttr ( "checked" );
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
	/**
	 *
	 * @param string $label        	
	 * @return \tian\ui\base\input\checkbox
	 */
	public function setLabel($label) {
		$this->label = $label;
		return $this;
	}
	public function html() {
		return $this->wrapBegin . $this->getNodeHtml () . $this->label . $this->wrapEnd;
	}
}
