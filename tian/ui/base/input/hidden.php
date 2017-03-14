<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base\input;

class hidden extends \tian\ui\base\formInput {
	public function __construct($value) {
		$this->selfclose = true;
		$this->tagName = "input";
		$this->attributes = array (
				"type" => "hidden",
				"value" => $value 
		);
		$this->formVisible = false;
	}
}