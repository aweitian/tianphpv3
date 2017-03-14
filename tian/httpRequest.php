<?php

/**
 * @author awei.tian
 * date: 2016-7-8
 * 说明:HTTP请求对象
 */
namespace tian;

class httpRequest {
	private static $inst;
	private function __construct() {
	}
	/**
	 *
	 * @return \tian\httpRequest
	 */
	public static function getInstance() {
		if (is_null ( self::$inst )) {
			self::$inst = new self ();
		}
		return self::$inst;
	}
	public function getQueryString() {
		return $_SERVER ['QUERY_STRING'];
	}
	/**
	 * 小写
	 *
	 * @return string
	 */
	public function getMethod() {
		return strtolower ( $_SERVER ['REQUEST_METHOD'] );
	}
	/**
	 * 返回本次提交是否是POST方式
	 *
	 * @return boolean
	 */
	public function isPost() {
		return strtoupper ( $_SERVER ['REQUEST_METHOD'] ) == 'POST';
	}
	/**
	 * 获取URL全部,不包括USER AND PASS
	 *
	 * @return string
	 */
	public function getCurUrl() {
		$url = 'http://';
		if (isset ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] == 'on') {
			$url = 'https://';
		}
		if ($_SERVER ['SERVER_PORT'] != '80') {
			$url .= $_SERVER ['SERVER_NAME'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
		} else {
			$url .= $_SERVER ['SERVER_NAME'] . $_SERVER ['REQUEST_URI'];
		}
		return $url;
	}
	/**
	 * http://localhost/we/werw?wer=fwer#sdfsd => /we/werw?wer=fwer
	 *
	 * @return string
	 */
	public function requestUri() {
		if (isset ( $_SERVER ['HTTP_X_REWRITE_URL'] )) {
			$uri = $_SERVER ['HTTP_X_REWRITE_URL'];
		} elseif (isset ( $_SERVER ['REQUEST_URI'] )) {
			$uri = $_SERVER ['REQUEST_URI'];
		} elseif (isset ( $_SERVER ['ORIG_PATH_INFO'] )) {
			$uri = $_SERVER ['ORIG_PATH_INFO'];
			if (! empty ( $_SERVER ['QUERY_STRING'] )) {
				$uri .= '?' . $_SERVER ['QUERY_STRING'];
			}
		} else {
			$uri = '';
		}
		return $uri;
	}
}