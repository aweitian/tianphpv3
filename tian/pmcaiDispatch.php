<?php

/**
 * @Author: awei.tian
 * @Date: 2016年12月6日
 * @Desc: 按规则创建对象和调用相应的方法
 * 		对待数字CTONROL和ACTION的处理方式
 * 			如果设置不支持，直接返回FALSE
 * 			如果PREFIX不空用PREFIX，
 * 			否则NUMERIC_PREFIX不空用NUMERIC_PREFIX
 * 			否则返回
 * 
 * 	EG:
 * 		prefix = "dd" 	numeric_pre="_"		====>	dd0123
 * 		prefix = "" 	numeric_pre="_"		====>	_0123
 * 	CONTROL 和 ACTION 支持前后缀
 * 		对待ACTION的调用第一个参数为info
 * 依赖:
 */
namespace tian;

class pmcaiDispatch {
	/**
	 * IController接口名
	 *
	 * @var string
	 */
	private $privChk = "checkPrivilege";
	/**
	 * IActionNotFound接口名
	 *
	 * @var string
	 */
	private $no_act = "Action_not_found";
	
	/**
	 * 可用变量{control}
	 *
	 * @var string
	 */
	private $control_pattern = "{control}";
	/**
	 * 可用变量{action}
	 *
	 * @var string
	 */
	private $action_pattern = "{action}Action";
	private $default_control = "main";
	private $default_action = "welcome";
	private $numeric_action_support = true;
	private $numeric_action_prefix = "_"; // 如果numericSupport为真，并且action_prefix为空才使用它
	private $numeric_control_support = true;
	private $numeric_control_prefix = "_"; // 如果numericSupport为真，并且control_prefix为空才使用它
	/**
	 * 可用变量{moduleloc},{control}
	 *
	 * @var string
	 */
	private $loc_pattern = "{moduleloc}/{control}/control.php";
	/**
	 * 可用变量{control}
	 *
	 * @var string
	 */
	private $namespace_pattern = "\app\modules\def\{control}";
	/**
	 *
	 * @var string : module loc
	 */
	private $loc;
	
	/**
	 *
	 * @var \ReflectionClass
	 */
	private $rc;
	private $rc_action;
	private $rc_arg = array ();
	/**
	 *
	 * @param array $conf
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
	public function __construct(array $conf) {
		$allowCnf = array (
				"control_pattern",
				"action_pattern",
				"numeric_control_support",
				"numeric_control_prefix",
				"numeric_action_support",
				"numeric_action_prefix",
				"default_control",
				"default_action",
				"loc_pattern",
				"namespace_pattern" 
		);
		foreach ( $conf as $key => $val ) {
			if (property_exists ( $this, $key ) && in_array ( $key, $allowCnf )) {
				$this->{$key} = $val;
			}
		}
	}
	public function setDispatchArgs(array $arg = array()) {
		$this->rc_arg = $arg;
	}
	/**
	 *
	 * @param string $m
	 *        	module loc
	 * @param string $c        	
	 * @param string $a        	
	 * @param string $i        	
	 * @return boolean
	 */
	public function dispatch($m, $c, $a, $i) {
		return $this->probe ( $m, $c, $a );
	}
	/**
	 * 调用ACTION
	 */
	public function invoke() {
		$controller = $this->rc->newInstance ();
		$method = $this->rc->getMethod ( $this->rc_action );
		$method->invokeArgs ( $controller, $this->rc_arg );
		return;
	}
	private function probe($m, $c, $a) {
		$control = $this->filterControl ( $c );
		if ($control === false) {
			
			\tian\log::d ( "dispatching://control numeric is not support yet" );
			return false;
		}
		$control_suffix = strtr ( $this->control_pattern, array (
				"{control}" => $control 
		) );
		$namespace = strtr ( $this->namespace_pattern, array (
				'{control}' => $control 
		) );
		$control_suffix_ns = $namespace . "\\" . $control_suffix;
		$controlLoc = strtr ( $this->loc_pattern, array (
				'{moduleloc}' => $m,
				'{control}' => $control 
		) );
		
		\tian\log::d ( "dispatching://prepare to check class exists named $control_suffix_ns" );
		if (! class_exists ( $control_suffix_ns, false )) {
			
			\tian\log::d ( "dispatching://class not found,finding file in $controlLoc" );
			if (file_exists ( $controlLoc )) {
				
				\tian\log::d ( "dispatching://control file found, require the control file at $controlLoc" );
				require_once ($controlLoc);
			}
			
			\tian\log::d ( "dispatching://recheck class exists" );
			if (! class_exists ( $control_suffix_ns, false )) {
				
				\tian\log::d ( "dispatching://trigger control not exist" );
				return false;
			} else {
				
				\tian\log::d ( "dispatching://trigger control exist" );
				return $this->searchAction ( $control_suffix_ns, $a );
			}
		} else {
			
			\tian\log::d ( "dispatching://trigger control exist" );
			return $this->searchAction ( $control_suffix_ns, $a );
		}
		return false;
	}
	private function searchAction($controller, $action) {
		\tian\log::d ( "dispatching://init control class" );
		$rc = new \ReflectionClass ( $controller );
		
		\tian\log::d ( "dispatching://check control implements the iController interface" );
		if ($rc->implementsInterface ( '\tian\interfaces\IController' )) {
			
			\tian\log::d ( "dispatching://control implemented the iController interface" );
			// 权限检查
			
			if (! $rc->hasMethod ( $this->privChk ) or ! $rc->getMethod ( $this->privChk )->isStatic ()) {
				
				\tian\log::d ( "dispatching://controller not function " . $this->privChk );
				return false;
			}
			$privilege = call_user_func_array ( $controller . "::" . $this->privChk, array (
					\tian\identityToken::getInstance () 
			) );
			
			\tian\log::d ( "dispatching://execute the check privilege function" );
			if ($privilege !== true) {
				
				\tian\log::d ( "dispatching://blocked by the privilege check" );
				return false;
			} else {
				
				\tian\log::d ( "dispatching://pass the privilege check" );
				$action = $this->filterAction ( $action );
				$action_suffix = strtr ( $this->action_pattern, array (
						"{action}" => $action 
				) );
				
				\tian\log::d ( "dispatching://assign the action:$action_suffix" );
				// action存在
				
				\tian\log::d ( "dispatching://check action exists" );
				
				if ($rc->hasMethod ( $action_suffix )) {
					
					\tian\log::d ( "dispatching://action found,and invoked,dispatch end." );
					$this->rc = $rc;
					$this->rc_action = $action_suffix;
					return true;
					// ACTION不存在，但实现了iActionNotFound接口
				} elseif ($rc->implementsInterface ( '\tian\interfaces\IActionNotFound' )) {
					
					\tian\log::d ( "dispatching://action not found,but the control immplements the IActionNotFound interface" );
					$action = $this->no_act;
					$this->rc_action = $action;
					
					\tian\log::d ( "dispatching://action is assigned to $action" );
					$this->rc = $rc;
					return true;
				} else {
					
					\tian\log::d ( "dispatching://action not found,and the control no implemtns the IActionNotFound interface,dispatch end" );
					return false;
				}
			}
		} else {
			
			\tian\log::d ( "dispatching://the class have not implements iController interfaces,dispatch end" );
			return false;
		}
	}
	private function filterControl($control) {
		if ($control == "") {
			return $this->default_control;
		} else if (preg_match ( "/^[0-9]/", $control )) {
			if ($this->numeric_control_support) {
				if ($this->control_prefix == "" && $this->numeric_control_prefix == "") {
					return false;
				} else {
					if ($this->control_prefix != "") {
						return $control;
					} else {
						return $this->numeric_control_prefix . $control;
					}
				}
			}
			return false;
		} else {
			return $control;
		}
	}
	private function filterAction($action) {
		if ($action == "") {
			return $this->default_action;
		} else if (preg_match ( "/^[0-9]/", $action )) {
			if ($this->numeric_action_support) {
				if ($this->action_prefix == "" && $this->numeric_action_prefix == "") {
					return false;
				} else {
					if ($this->action_prefix != "") {
						return $action;
					} else {
						return $this->numeric_action_prefix . $action;
					}
				}
			}
			return false;
		} else {
			return $action;
		}
	}
}