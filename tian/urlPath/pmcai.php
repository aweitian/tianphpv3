<?php

/**
 * @author awei.tian
 * date: 2013-9-16
 * 说明:
 * 	本类主要是按PMCAI规则解析 URL的PATH部分
 * 	P为HTTP入口目录
 * 	m为模块存放目录
 * 	c为控制器名称
 * 	a为动作名称
 * 	i为动作后剩余部分,称为INFO
 * 依赖:
 * 	\tian\url
 */
namespace tian\urlPath;

class pmcai {
	private $http_entry;
	private $http_entry_len;
	private $mask;
	private $path;
	public $prefix = "";
	public $module = array ();
	public $info = array ();
	public $control = "";
	public $action = "";
	/**
	 * 去掉HTTP_ENTRY的URL PATH部分
	 *
	 * @var string
	 */
	public $realpath;
	/**
	 *
	 * @param string $url
	 *        	完整路径
	 * @param string $http_entry
	 *        	入口路径
	 */
	public function __construct($url, $http_entry = "", $mask = "ca") {
		if ($url) {
			$url = new \tian\url ( $url );
			$this->path = $url->path;
			$this->http_entry = $http_entry;
			$this->prefix = $http_entry;
			$this->http_entry_len = strlen ( $this->http_entry );
			$this->mask = $mask;
			if ($this->http_entry_len && substr ( $this->path, 0, $this->http_entry_len ) == $this->http_entry) {
				$this->realpath = substr ( $this->path, $this->http_entry_len );
			} else {
				$this->realpath = $url;
			}
		}
	}
	/**
	 *
	 * @param string $url        	
	 * @param array $conf        	
	 * @return \tian\urlPath\pmcai
	 */
	public static function getInstance($url, array $conf = array()) {
		$http_entry = isset ( $conf ["http_entry"] ) ? $conf ["http_entry"] : "";
		$mask = isset ( $conf ["mask"] ) ? $conf ["mask"] : "ca";
		return new self ( $url, $http_entry, $mask );
	}
	public function setMask($mask) {
		$this->mask = $mask;
	}
	public function parse() {
		if (! pmcai::isValidMask ( $this->mask ))
			return;
		if ($this->path == "")
			return;
		$mca_path = $this->realpath;
		$mca_arr = explode ( "/", trim ( $mca_path, "/" ) );
		$x = 0;
		$pmcaii_mask_arr = str_split ( $this->mask );
		
		while ( $x < count ( $mca_arr ) ) {
			if ($x >= count ( $pmcaii_mask_arr ))
				$z = "i";
			else
				$z = $pmcaii_mask_arr [$x];
			$v = $mca_arr [$x];
			switch ($z) {
				case "m" :
					$this->module [] = $v;
					break;
				case "c" :
					$this->control = $v;
					break;
				case "a" :
					$this->action = $v;
					break;
				case "i" :
					$this->info [] = $v;
					break;
			}
			$x ++;
		}
	}
	public static function isValidMask($mask) {
		return preg_match ( "/^m*(ca|c)?$/", $mask );
	}
}
