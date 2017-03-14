<?php

/**
 * @Author: awei.tian
 * @Date: 2016年12月9日
 * @Desc: 
 * 依赖:
 */
namespace tian;

abstract class control implements \tian\interfaces\IController {
	/**
	 *
	 * @var \tian\httpRequest
	 */
	public $request;
	/**
	 *
	 * @var \tian\httpResponse
	 */
	public $response;
	
	/**
	 *
	 * @var \tian\identityToken
	 */
	public $identityToken;
	public function __construct() {
		$this->request = \tian\httpRequest::getInstance ();
		$this->response = \tian\httpResponse::getInstance ();
		$this->identityToken = \tian\identityToken::getInstance ();
	}
	public function isPost() {
		return strtoupper ( $_SERVER ['REQUEST_METHOD'] ) == 'POST';
	}
}