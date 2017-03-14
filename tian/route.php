<?php

/**
 * 路由的作用就是匹配URL，成功，返回PMCAI
 * @author awei.tian
 * 使用路由表，支持三种方式
 * 		equal(强制路由,完全相等,一般用于解决CONTROL和MODULE冲突时使用)
 * 		regExp(正则匹配)
 * 		startWith(以什么开头 比如/priv能匹配/priv,/priv?a=b,/priv/a/b,不能匹配/privilege)
 * 		default(默认路由)
 * 依赖:
 * 	\tian\utils\utility
 */
namespace tian;

class route {
	private $url;
	/**
	 * urlPath 启动参数
	 *
	 * @var array 具体以module构造函数传入的参数为准
	 *      如果是pmcai类型的array(
	 *      "http_entry" => "",
	 *      "mask" => "ca",
	 *      )
	 */
	public $matchedUrlPathInitArgs;
	/**
	 * 路由表 格式参考 array( "equal" "regExp" "startWith" "default" )
	 *
	 * @param string $url
	 *        	(httpRequest\requestUri() 结果)
	 */
	public function __construct($url) {
		$u = explode ( "?", $url, 2 );
		$this->url = $u [0];
	}
	/**
	 * 只比较url PATH部分,不考虑PREFIX(prefix包含在path中)
	 *
	 * @param array $rt
	 *        	array("type"=>"pmcai|pmi|arr",array $conf = array())
	 * @return boolean
	 */
	public function equalMatch(array $rt) {
		foreach ( $rt as $key => $item ) {
			if ($key == $this->url) {
				\tian\log::d("route://equal route matched.");
				$this->matchedUrlPathInitArgs = $item;
				return true;
			}
		}
		return false;
	}
	/**
	 * 只比较url PATH部分,不考虑PREFIX(prefix包含在path中)
	 *
	 * @param array $rt
	 *        	array("type"=>"pmcai|pmi|arr",array $conf = array())
	 * @return boolean
	 */
	public function regexpMatch(array $rt) {
		foreach ( $rt as $key => $item ) {
			if (preg_match ( "#^{$key}\$#", $this->url )) {
				\tian\log::d("route://regexp route matched.");
				$this->matchedUrlPathInitArgs = $item;
				return true;
			}
		}
		return false;
	}
	/**
	 * 只比较url PATH部分,不考虑PREFIX(prefix包含在path中)
	 *
	 * @param array $rt
	 *        	array("type"=>"pmcai|pmi|arr",array $conf = array())
	 * @return boolean
	 */
	public function startwithMatch(array $rt) {
		foreach ( $rt as $key => $item ) {
			if (\tian\utils\utility::endsWith ( $key, "/" )) {
				if (strpos ( $this->url, $key ) === 0) {
					\tian\log::d("route://startwith route matched.");
					$this->matchedUrlPathInitArgs = $item;
					return true;
				}
			} else {
				if ($key === $this->url || strpos ( $this->url, $key . "/" ) === 0) {
					\tian\log::d("route://startwith route matched.");
					$this->matchedUrlPathInitArgs = $item;
					return true;
				}
			}
		}
		return false;
	}
	/**
	 *
	 * @param array $rt
	 *        	array("type"=>"pmcai|pmi|arr",array $conf = array())
	 * @return boolean
	 */
	public function defaultMatch(array $rt) {
		\tian\log::d("route://default route matched.");
		$this->matchedUrlPathInitArgs = $rt;
		return true;
	}
}
