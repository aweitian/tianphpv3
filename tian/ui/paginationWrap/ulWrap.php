<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月12日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\paginationWrap;

class ulWrap extends paginationWrap {
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $ul;
	public function wrap(\tian\ui\pagination $pagination) {
		$this->initSelect ( $pagination );
		$this->ul = new \tian\ui\base\element ( "ul" );
		if ($pagination->pagination->hasPre ()) {
			$li = new \tian\ui\base\element ( "li" );
			$a = new \tian\ui\base\element ( "a" );
			$li->appendNode ( $a );
			$a->appendNode ( new \tian\ui\base\textnode ( $pagination->pagination->getPre () ) );
			$pagination->btnPriv = $li;
		}
		
		for($i = $pagination->getStartPage (); $i <= $pagination->getMaxPage (); $i ++) {
			$li = new \tian\ui\base\element ( "li" );
			$a = new \tian\ui\base\element ( "a" );
			$li->appendNode ( $a );
			$a->appendNode ( new \tian\ui\base\textnode ( $i ) );
			$pagination->btnGrp->appendNode ( $li );
		}
		
		if ($pagination->pagination->hasNext ()) {
			$li = new \tian\ui\base\element ( "li" );
			$a = new \tian\ui\base\element ( "a" );
			$li->appendNode ( $a );
			$pagination->btnNext = $li;
			$a->appendNode ( new \tian\ui\base\textnode ( $pagination->pagination->getNext () ) );
		}
		$pagination->btnGrp = $this->ul;
	}
	private function initSelect(\tian\ui\pagination $pagination) {
		if ($pagination->selectEnabled) {
			$pagination->select = new \tian\ui\base\element ( "select" );
			for($i = 0; $i < $pagination->pagination->getMaxPage (); $i ++) {
				$option = new \tian\ui\base\element ( "option" );
				$option->setText ( $i + 1 );
				$pagination->select->appendNode ( $option );
			}
		}
	}
}