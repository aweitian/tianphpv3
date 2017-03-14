<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月7日
 * @Desc: 
 * 依赖:
 */
namespace tian\ui\tableWrap;

class tbWrap extends \tian\ui\tableWrap\tableWrap {
	/**
	 *
	 * @var \tian\ui\table
	 */
	public $tb;
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
	public function __construct() {
	}
	/**
	 *
	 * @return number
	 */
	public function getColspan() {
		return isset ( $this->tb->data [0] ) ? count ( $this->tb->data [0] ) : 0;
	}
	public function wrap(\tian\ui\table $table) {
		$this->tb = $table;
		$this->table = new \tian\ui\base\element ( "table" );
		$this->caption = new \tian\ui\base\element ( "caption" );
		$this->thead = new \tian\ui\base\element ( "thead" );
		$tr = new \tian\ui\base\element ( "tr" );
		foreach ( $this->tb->alias () as $field => $alias ) {
			$th = new \tian\ui\base\element ( "th" );
			$th->appendNode ( new \tian\ui\base\textnode ( $alias ) );
			$tr->appendNode ( $th );
		}
		$this->thead->appendNode ( $tr );
		
		$this->tbody = new \tian\ui\base\element ( "tbody" );
		$this->tfoot = new \tian\ui\base\element ( "tfoot" );
		
		foreach ( $this->tb->data as $key => $item ) {
			$tr = new \tian\ui\base\element ( "tr" );
			// 这一层循环代替TD层循环
			foreach ( $this->tb->alias () as $field => $alias ) {
				$content = $alias;
				$td = new \tian\ui\base\element ( "td" );
				if (array_key_exists ( $field, $item )) {
					$content = $item [$field];
				}
				
				if (array_key_exists ( $field, $this->tb->dataFilter ) && is_callable ( $this->tb->dataFilter [$field] )) {
					$content = call_user_func_array ( $this->tb->dataFilter [$field], array (
							$this,
							$tr,
							$td,
							$item,
							$content 
					) );
				}
				
				// domain
				if (array_key_exists ( $field, $this->tb->domain )) {
					$ret = array ();
					$set = explode ( ",", $content );
					foreach ( $set as $s ) {
						if (array_key_exists ( $s, $this->tb->domain [$field] )) {
							$ret [] = $this->tb->domain [$field] [$s];
						}
					}
					$content = join ( ",", $ret );
				}
				$td->appendNode ( new \tian\ui\base\textnode ( $content ) );
				$tr->appendNode ( $td );
			}
			$this->tbody->appendNode ( $tr );
		}
		$this->table->appendNode ( $this->caption );
		$this->table->appendNode ( $this->thead );
		$this->table->appendNode ( $this->tbody );
		$this->table->appendNode ( $this->tfoot );
		$table->table = $this->table;
	}
}