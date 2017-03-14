<?php

/**
 * Date: Apr 13, 2016
 * Author: Awei.tian
 * Description: 
 */
namespace tian;

class rirResult {
	const RESULTOK = 0;
	public $result = 0;
	public $info;
	public $return;
	public function __construct($result = rirResult::RESULTOK, $info = "", $return = array()) {
		$this->result = $result;
		$this->info = $info;
		$this->return = $return;
	}
	public function isTrue() {
		return $this->result == rirResult::RESULTOK;
	}
	public function toJSON() {
		return json_encode ( array (
				"result" => $this->result,
				"info" => $this->info,
				"return" => $this->return 
		) );
	}
}