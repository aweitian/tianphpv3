<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base\input;

class image extends \tian\ui\base\formInput {
	public function __construct($src = "") {
		$this->selfclose = true;
		$this->tagName = "input";
		$this->attributes = array (
				"type" => "image",
				"src" => $src 
		);
	}
	public function setSrc($src) {
		$this->setAttr ( "src", $src );
		return $this;
	}
}