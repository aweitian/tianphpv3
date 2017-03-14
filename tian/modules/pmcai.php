<?php

/**
 * @Author: awei.tian
 * @Date: 2016年12月7日
 * @Desc: 
 * 		关于MODULE LOCATION查找方法
 * 			1) 网址中的MODULE数组JOIN("/"),如果结果为空,第三步
 *			2) 然后查表,如果表为空,第三步，如果找到相应的KEY，返回对应的VALUE，没有找到第三步
 * 			3) 检查MODULE_LOC变量，如果为空，异常（找不到模块目录）
 * 			4) 返回MODULE_LOC
 * 	
 * 现在对把参数传到CONSTRUCT还是ACTION中没有概念，现在把它传到ACTION中(传入URLPATH到ACTION中)
 * 		
 * 依赖:
 */
namespace tian\modules;

class pmcai implements \tian\interfaces\IModule {
	private $rt;
	private $dpcnf;
	private $http_entry;
	/**
	 *
	 * @var \tian\urlPath\pmcai
	 */
	private $pmcai;
	private $routeFlag = false;
	private $module_loc = "";
	private $module_arr = array ();
	private $dispatcher;
	/**
	 *
	 * @param array $rt
	 *        	array(
	 *        	------"equal" => array(
	 *        	---------"/path/to/dst" => array(
	 *        	---------"http_entry" => "",
	 *        	---------"mask" => "ca"
	 *        	------)
	 *        	---),
	 *        	---"regExp" => array(),
	 *        	---"startWith" => array(),
	 *        	---"default" => array("http_entry" => "","mask" => "ca"),
	 *        	)
	 *        	
	 * @param array $dpcnf
	 *        	array(
	 *        	"control_pattern" => "{control}",
	 *        	"action_pattern" => "{action}Action",
	 *        	"numeric_control_support" => true,
	 *        	"numeric_control_prefix" => "_",
	 *        	"numeric_action_support" => true,
	 *        	"numeric_action_prefix" => "_",
	 *        	"default_control" => "main",
	 *        	"default_action" => "welcome",
	 *        	"loc_pattern" => "{moduleloc}/{control}/{control_suffix}.php",
	 *        	"namespace_pattern" => "\{control}\{control_suffix",
	 *        	)
	 */
	public function __construct(array $rt = array(), array $dpcnf = array(), $ml = "", array $ma = array()) {
		$this->rt = $rt;
		$this->dpcnf = $dpcnf;
		$this->module_loc = $ml;
		$this->module_arr = $ma;
	}
	
	/**
	 *
	 * @return bool
	 */
	public function match($requestUri) {
		\tian\log::d ( "module://URL is $requestUri" );
		$route = new \tian\route ( $requestUri );
		foreach ( $this->rt as $t => $r ) {
			switch ($t) {
				case "equal" :
					if ($route->equalMatch ( $r )) {
						\tian\log::d ( "module://equal args is " . var_export ( $route->matchedUrlPathInitArgs, true ) );
						$this->pmcai = \tian\urlPath\pmcai::getInstance ( $requestUri, $route->matchedUrlPathInitArgs );
						$this->pmcai->parse ();
						\tian\log::d ( "module://pmcai C:" . $this->pmcai->control . ",A:" . $this->pmcai->action );
						$this->routeFlag = true;
						return true;
					}
					break;
				case "regExp" :
					if ($route->regexpMatch ( $r )) {
						\tian\log::d ( "module://regExp args is " . var_export ( $route->matchedUrlPathInitArgs, true ) );
						$this->pmcai = \tian\urlPath\pmcai::getInstance ( $requestUri, $route->matchedUrlPathInitArgs );
						$this->pmcai->parse ();
						\tian\log::d ( "module://pmcai C:" . $this->pmcai->control . ",A:" . $this->pmcai->action );
						$this->routeFlag = true;
						return true;
					}
					break;
				case "startWith" :
					if ($route->startwithMatch ( $r )) {
						\tian\log::d ( "module://startWith args is " . var_export ( $route->matchedUrlPathInitArgs, true ) );
						$this->pmcai = \tian\urlPath\pmcai::getInstance ( $requestUri, $route->matchedUrlPathInitArgs );
						$this->pmcai->parse ();
						\tian\log::d ( "module://pmcai C:" . $this->pmcai->control . ",A:" . $this->pmcai->action );
						$this->routeFlag = true;
						return true;
					}
					break;
				case "default" :
					if ($route->defaultMatch ( $r )) {
						\tian\log::d ( "module://default route args is " . var_export ( $route->matchedUrlPathInitArgs, true ) );
						$this->pmcai = \tian\urlPath\pmcai::getInstance ( $requestUri, $route->matchedUrlPathInitArgs );
						$this->pmcai->parse ();
						\tian\log::d ( "module://pmcai C:" . $this->pmcai->control . ",A:" . $this->pmcai->action );
						$this->routeFlag = true;
						return true;
					}
					break;
			}
		}
		$this->routeFlag = false;
		return false;
	}
	/**
	 *
	 * @return bool
	 */
	public function dispatch() {
		if (! $this->routeFlag)
			return false;
		$this->dispatcher = new \tian\pmcaiDispatch ( $this->dpcnf );
		$p = $this->pmcai;
		$this->dispatcher->setDispatchArgs ( array (
				$p 
		) );
		$modLoc = $this->findModLoc ( $p->module );
		if (! $modLoc)
			return false;
		return $this->dispatcher->dispatch ( $modLoc, $p->control, $p->action, $p->info );
	}
	
	/**
	 * 调用ACTION
	 *
	 * @return void
	 */
	public function invoke() {
		$this->dispatcher->debug = true;
		return $this->dispatcher->invoke ();
	}
	private function findModLoc(array $ma) {
		$ma_str = join ( "/", $ma );
		if ($ma_str != "" && array_key_exists ( $ma_str, $this->module_arr )) {
			\tian\log::d ( "Dispatch://module loc:" . $this->module_arr [$ma_str] );
			return $this->module_arr [$ma_str];
		}
		if ($this->module_loc == "") {
			\tian\log::e ( "MODULE LOCATION NOT FOUND" );
			return false;
		}
		\tian\log::d ( "Dispatch://module loc:" . $this->module_loc );
		return $this->module_loc;
	}
}