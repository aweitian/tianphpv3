<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月7日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\formWrap;

class tbWrap extends \tian\ui\formWrap\formWrap {
	/**
	 *
	 * @var \tian\ui\form
	 */
	public $form;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $table;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $thead;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $tbody;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $tfoot;
	/**
	 *
	 * @var \tian\ui\base\element
	 */
	public $caption;
	public $alias;
	public function __construct() {
	}
	public function wrap(\tian\ui\form $form) {
		$this->form = $form;
		$this->alias = $form->alias ();
		$this->table = new \tian\ui\base\element ( "table" );
		$this->caption = new \tian\ui\base\element ( "caption" );
		$this->thead = new \tian\ui\base\element ( "thead" );
		$this->tbody = new \tian\ui\base\element ( "tbody" );
		$this->tfoot = new \tian\ui\base\element ( "tfoot" );
		$this->table->appendNode ( $this->caption );
		$this->table->appendNode ( $this->thead );
		$this->table->appendNode ( $this->tbody );
		$this->table->appendNode ( $this->tfoot );
		foreach ( $this->alias as $field => $alias ) {
			if (! $this->form->hasElem ( $field ))
				continue;
			$tr = new \tian\ui\base\element ( "tr" );
			if ($alias) {
				$td1 = new \tian\ui\base\element ( "td" );
				$td1->appendNode ( new \tian\ui\base\textnode ( $alias ) );
				$td2 = new \tian\ui\base\element ( "td" );
				$node = $this->form->getNode ( $field );
				$td2->appendNode ( $node );
				$tr->appendNode ( $td1 );
				$tr->appendNode ( $td2 );
				$this->tbody->appendNode ( $tr );
			} else {
				$td2 = new \tian\ui\base\element ( "td", array (
						"colspan" => 2 
				) );
				$node = $this->form->getNode ( $field );
				$td2->appendNode ( $node );
				$tr->appendNode ( $td2 );
				$this->tbody->appendNode ( $tr );
			}
		}
		$this->form->getFormElement ()->clearNode ();
		$this->form->getFormElement ()->appendNode ( $this->table );
	}
}