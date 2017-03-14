<?php

/**
 * @data 2016-7-7
 * @author awei.tian
 * @descript
 * 		模板数据
 * 		选择TYPE_INCLUDE_NOW，INCLUDE在LAYOUT之前，
 * 		选择TYPE_INCLUDE_DELAY，INCLUDE在LAYOUT之后
 * 		
 */
namespace tian;

class defTplData {
	private static $inst;
	const TYPE_STATIC_HTML = 0;
	const TYPE_INCLUDE_NOW = 1;
	const TYPE_INCLUDE_DELAY = 2;
	public $model;
	public $title;
	public $description;
	public $keyword;
	private $tpl = array ();
	private $html = array ();
	/**
	 * 用于TYPE_INCLUDE_NOW类型模板设置变量，然后在LAYOUT中使用
	 *
	 * @var array
	 */
	private $vars = array ();
	private $layout;
	private $callback_before_response = null;
	private $callback_after_response = null;
	private $themeRoot;
	/**
	 *
	 * @param string $theme
	 *        	主题根目录
	 * @param string $model        	
	 */
	private function __construct($theme_root, $model = null) {
		$this->themeRoot = $theme_root;
		$this->model = $model;
		$this->init ();
	}
	
	/**
	 *
	 * @param string $key        	
	 * @param unknown $val        	
	 * @return \tian\defTplData
	 */
	public function setVar($key, $val) {
		$this->vars [$key] = $val;
		return $this;
	}
	/**
	 *
	 * @param string $key        	
	 * @param string $def        	
	 * @return Ambigous <string, multitype:>
	 */
	public function getVar($key, $def = "") {
		return isset ( $this->vars [$key] ) ? $this->vars [$key] : $def;
	}
	
	/**
	 *
	 * @return \tian\defTplData
	 */
	public static function getInstance($theme, $model = null) {
		if (is_null ( self::$inst )) {
			self::$inst = new self ( $theme, $model );
		}
		return self::$inst;
	}
	/**
	 * 直接设置CONTENT
	 *
	 * @param string $type
	 *        	(defTplData::TYPE_INCLUDE_NOW/TYPE_INCLUDE_DELAY/TYPE_STATIC_HTML)
	 * @param string $data(一维的)
	 *        	如果是前两个类型，DATA的元素为两个，第一个为TPL文件路径，两个为数组，进行EXTRACT
	 * @return \tian\defTplData
	 */
	public function push($type, $data) {
		switch ($type) {
			case defTplData::TYPE_INCLUDE_NOW :
				if (is_array ( $data ) && count ( $data ) == 2 && is_string ( $data [0] ) && is_array ( $data [1] )) {
					$this->_fetch ( $data [0], $data [1] );
				} else {
					\tian\log::e ( "Class:" . __CLASS__ . ",line:" . __LINE__ );
					return;
				}
				break;
			case defTplData::TYPE_INCLUDE_DELAY :
				if (is_array ( $data ) && count ( $data ) == 2 && is_string ( $data [0] ) && is_array ( $data [1] )) {
					$this->_feed ( $data [0], $data [1] );
				} else {
					\tian\log::e ( "Class:" . __CLASS__ . ",line:" . __LINE__ );
					return;
				}
				break;
			default :
				$this->html = array (
						0,
						$data 
				);
				break;
		}
		return $this;
	}
	/**
	 *
	 * @param string $tpl        	
	 * @param array $data        	
	 * @return string
	 */
	public function fetch($tpl, $data = array()) {
		ob_start ();
		extract ( $data );
		include $tpl;
		$html = ob_get_contents ();
		ob_end_clean ();
		return $html;
	}
	
	/**
	 * 立即INCLUDE
	 *
	 * @param string $tpl
	 *        	tpl file path
	 * @param array $data
	 *        	环境数据
	 * @return \tian\defTplData
	 */
	private function _fetch($tpl, $data) {
		ob_start ();
		extract ( $data );
		include $tpl;
		$this->html [] = array (
				0,
				ob_get_contents () 
		);
		ob_end_clean ();
		return $this;
	}
	/**
	 * 按从上到下正常顺序INCLUDE
	 *
	 * @param string $tpl
	 *        	tpl文件路径
	 * @param unknown $data
	 *        	tpl文件数据环境
	 */
	private function _feed($tpl, $data) {
		$this->html [] = array (
				1,
				array (
						$tpl,
						$data 
				) 
		);
		return $this;
	}
	/**
	 * 如果要包含login.layout.php,只要传入login
	 *
	 * @param string $layout        	
	 * @return \tian\defTplData
	 */
	public function setLayout($layout = "") {
		if ($layout != "") {
			if (preg_match ( "/^\w+$/", $layout ) && file_exists ( $this->themeRoot . "/" . $layout . ".layout.php" )) {
				$this->layout = $this->themeRoot . "/" . $layout . ".layout.php";
			} else {
				$this->layout = $layout;
			}
		} else {
			$this->layout = $this->themeRoot . "/layout.php";
		}
		return $this;
	}
	/**
	 *
	 * @param callback $call        	
	 * @return \tian\defTplData
	 */
	public function setBeforeResponseCallback($call) {
		if (is_callable ( $call )) {
			$this->callback_before_response = $call;
		}
		return $this;
	}
	/**
	 *
	 * @param callback $call        	
	 * @return \tian\defTplData
	 */
	public function setBeforeAfterCallback($call) {
		if (is_callable ( $call )) {
			$this->callback_after_response = $call;
		}
		return $this;
	}
	/**
	 * 没有EXIT，只是调用了两个CALLBACK,中间INCLUDE了LAYOUT
	 *
	 * @return \tian\defTplData
	 */
	public function reponse() {
		if (is_callable ( $this->callback_before_response )) {
			call_user_func ( $this->callback_before_response, $this );
		}
		
		include $this->layout;
		
		if (is_callable ( $this->callback_after_response )) {
			call_user_func ( $this->callback_after_response, $this );
		}
		return $this;
	}
	/**
	 * 输出内容，包含DELAY的文件
	 *
	 * @return \tian\defTplData
	 */
	public function outputContent() {
		foreach ( $this->html as $tpl ) {
			if ($tpl [0] == 0) {
				print $tpl [1];
			} else {
				extract ( $tpl [1] [1] );
				include $tpl [1] [0];
			}
		}
		return $this;
	}
	private function init() {
		$this->title = '';
		$this->keyword = '';
		$this->description = '';
	}
}