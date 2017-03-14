<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base;

class formInput extends \tian\ui\base\element {
	public $alias;
	public $name;
	public function setName($name) {
		$this->name = $name;
		return parent::setName ( $name );
	}
	public function setAttr($ak, $av = null) {
		if (strtolower ( $ak ) == "name") {
			$this->name = $av;
		}
		return parent::setAttr ( $ak, $av );
	}
}
