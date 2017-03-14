<?php

/**
 * @author:awei.tian
 * @date:2013-12-17
 * @functions:对QUERY部分数组化操作
 * 其它部分直接设置获取
 * scheme - e.g. http
 * host 
 * port
 * user
 * pass
 * path
 * query - after the question mark ?
 * fragment - after the hashmark #
 */
namespace tian;

class url {
	private $part;
	// true为返回相对，false绝对
	private $retMode = true;
	private $queryArr = array ();
	/**
	 *
	 * @param string $url        	
	 */
	public function __construct($url) {
		$this->part = array (
				"scheme",
				"host",
				"port",
				"user",
				"pass",
				"path",
				"query",
				"fragment" 
		);
		$urlArr = parse_url ( $url );
		if ($urlArr === false) {
			return;
		}
		foreach ( $this->part as $p ) {
			if (isset ( $urlArr [$p] )) {
				if ($p == "query") {
					parse_str ( $urlArr ["query"], $this->queryArr );
				}
				$this->{$p} = $urlArr [$p];
			}
		}
	}
	/**
	 * true返回相对URL
	 *
	 * @param bool $v        	
	 * @return \tian\url
	 */
	public function setReturnMode($v) {
		$this->retMode = ! ! $v;
		return $this;
	}
	/**
	 * $k "scheme","host","port","user","pass","path","query","fragment"
	 *
	 * @param string $k        	
	 * @param string/int $v        	
	 */
	public function __set($k, $v) {
		if (in_array ( $k, $this->part )) {
			$this->{$k} = $v;
		}
	}
	/**
	 * 不存在返回NULL
	 *
	 * @param string $k
	 *        	有效值:("scheme","host","port","user","pass","path","query","fragment")
	 * @return string | NULL
	 */
	public function __get($k) {
		if (in_array ( $k, $this->part )) {
			return isset ( $this->{$k} ) ? $this->{$k} : null;
		}
	}
	/**
	 *
	 * @param string $key        	
	 * @param mixed $val        	
	 * @return \tian\url
	 */
	public function setQuery($key, $val) {
		$this->queryArr [$key] = $val;
		$this->query = http_build_query ( $this->queryArr );
		return $this;
	}
	
	/**
	 * 不存在返回NULL
	 *
	 * @param string $key        	
	 * @return string | NULL
	 *        
	 */
	public function getQuery($key) {
		if (isset ( $this->queryArr [$key] ))
			return $this->queryArr [$key];
		return null;
	}
	/**
	 *
	 * @param string $key        	
	 * @return \tian\url
	 */
	public function removeQuery($key) {
		$queryArr = utils\httpDataConverter::formToArray ( $this->query );
		if (array_key_exists ( $key, $this->queryArr ))
			unset ( $this->queryArr [$key] );
		$this->query = http_build_query ( $this->queryArr );
		return $this;
	}
	public function __toString() {
		$url = "";
		if (false === $this->retMode) {
			if (property_exists ( $this, "host" )) {
				if (property_exists ( $this, "scheme" )) {
					$url .= $this->scheme . ":";
				}
				$url .= "//";
				if (property_exists ( $this, "user" )) {
					$url .= $this->user;
					if (property_exists ( $this, "pass" )) {
						$url .= ":" . $this->pass;
					}
					$url .= "@";
				}
				
				$url .= $this->host;
				if (property_exists ( $this, "port" )) {
					$url .= ":" . $this->port;
				}
			}
		}
		if (property_exists ( $this, "path" )) {
			$url .= $this->path;
		}
		if (property_exists ( $this, "query" )) {
			$url .= "?" . $this->query;
		}
		if (property_exists ( $this, "fragment" )) {
			$url .= "#" . $this->fragment;
		}
		
		return $url;
	}
	/**
	 * 以STRING返回
	 *
	 * @return string
	 */
	public function toString() {
		return $this . "";
	}
}