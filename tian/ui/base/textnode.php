<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月3日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\base;

class textnode extends \tian\ui\base\node {
	private $textContent;
	public function __construct($text) {
		$this->setText ( $text );
	}
	public function setText($text) {
		$this->textContent = $text;
	}
	public function getNodeHtml() {
		return $this->textContent;
	}
}