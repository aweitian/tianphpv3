<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月9日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui;

class pagination {
	public $pagination;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $select;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $btnGrp;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $btnPriv;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $btnNext;
	public $selectEnabled = true;
	public function __construct(\tian\pagination $pagination) {
		$this->pagination = $pagination;
		$this->btnGrp = new \tian\ui\base\element ( "" );
		$this->btnPriv = null;
		$this->btnNext = null;
		$this->select = null;
	}
	public function setSelectEnabled($f) {
		$this->selectEnabled = $f;
		return $this;
	}
	public function wrap(\tian\ui\paginationWrap\paginationWrap $wrap) {
		$wrap->wrap ( $this );
		return $this;
	}
}