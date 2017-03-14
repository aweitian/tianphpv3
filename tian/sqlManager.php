<?php

/**
 * Date: Apr 20, 2016
 * Author: Awei.tian
 * Description: 
 */
namespace tian;

class sqlManager {
	/**
	 *
	 * @var SimpleXMLElement
	 */
	private $xml;
	public function __construct($name) {
		if (! file_exists ( $name )) {
			return;
		}
		$this->xml = simplexml_load_file ( $name );
	}
	public static function getInstance($name) {
		return new sqlManager ( $name );
	}
	public function getSql($xpath, $obj = array()) {
		$sql = $this->xml->xpath ( $xpath );
		if ($sql && is_array ( $sql ) && count ( $sql ) == 1) {
			$sql = $sql [0]->__toString ();
			if (! empty ( $obj ))
				return strtr ( $sql, $obj );
			else
				return $sql;
		}
		return "";
	}
}