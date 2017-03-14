<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月6日
 * @Desc: 
 * 		为COMPONENT设置一个合适的ELEMENT
 * 依赖:
 */
namespace tian\mysql;

use tian\urlPath\arr;

class fieldUI {
	/**
	 *
	 * @var \tian\data\component
	 */
	public $component;
	
	/**
	 *
	 * @var \tian\ui\base\formInput
	 */
	public $element;
	/**
	 * 数据格式为:
	 * 字段名 => element类型名
	 * element类型名(\tian\ui\base\input类型)
	 *
	 * @var array
	 */
	public $uiTypeMap = array ();
	public function __construct(\tian\data\component $component = NULL) {
		if (! is_null ( $component )) {
			$this->setComponent ( $component );
		}
	}
	public function setComponent(\tian\data\component $component) {
		$this->component = $component;
		return $this;
	}
	
	/**
	 * 对于类型为enum 或者 set时有用
	 *
	 * @param array $domain        	
	 * @return \tian\mysql\fieldUI
	 */
	public function setDomain(array $domain) {
		$this->component->domain = $domain;
		return $this;
	}
	
	/**
	 * 可用的类型有:
	 * button checkboxGrp date datetime
	 * file image image password
	 * radioGrp reset select submit
	 * text textarea
	 *
	 * @return \tian\mysql\fieldUI
	 */
	public function setUiTypeMap(array $map) {
		$this->uiTypeMap = $map;
		return $this;
	}
	/**
	 * 可用的类型有:
	 * button checkboxGrp date datetime
	 * file image image password
	 * radioGrp reset select submit
	 * text textarea
	 *
	 * @param string $field        	
	 * @param string $type        	
	 * @return \tian\mysql\fieldUI
	 */
	public function addUiTypeMap($field, $type) {
		$this->uiTypeMap [$field] = $type;
		return $this;
	}
	/**
	 *
	 * @param string $field        	
	 * @return \tian\mysql\fieldUI
	 */
	public function rmUiTypeMap($field) {
		if (isset ( $this->uiTypeMap [$field] ))
			unset ( $this->uiTypeMap [$field] );
		return $this;
	}
	
	/**
	 * 调用match过后，成员element可用
	 *
	 * @return boolean
	 */
	public function match() {
		$name = $this->component->name;
		if (! array_key_exists ( $name, $this->uiTypeMap ))
			return $this->defMatch ();
		$elementType = $this->uiTypeMap [$name];
		
		switch ($elementType) {
			case "button" :
				$this->element = new \tian\ui\base\input\button ( $this->component->default );
				return true;
			case "checkboxGrp" :
				$this->element = new \tian\ui\base\input\checkboxGrp ( $this->component->name, $this->component->domain, $this->component->default );
				return true;
			case "date" :
				$this->element = new \tian\ui\base\input\date ( $this->component->name, $this->component->default );
				return true;
			case "datetime" :
				$this->element = new \tian\ui\base\input\datetime ( $this->component->name, $this->component->default );
				return true;
			case "file" :
				$this->element = new \tian\ui\base\input\file ( $this->component->name, $this->component->default );
				return true;
			case "image" :
				$this->element = new \tian\ui\base\input\image ( $this->component->default );
				return true;
			case "password" :
				$this->element = new \tian\ui\base\input\password ( $this->component->name, $this->component->default );
				return true;
			case "radioGrp" :
				$this->element = new \tian\ui\base\input\radioGrp ( $this->component->name, $this->component->domain, $this->component->default );
				return true;
			case "reset" :
				$this->element = new \tian\ui\base\input\reset ( $this->component->default );
				return true;
			case "select" :
				$this->element = new \tian\ui\base\input\select ( $this->component->name, $this->component->domain, $this->component->default );
				return true;
			case "submit" :
				$this->element = new \tian\ui\base\input\submit ( $this->component->default );
				return true;
			case "text" :
				$this->element = new \tian\ui\base\input\text ( $this->component->name, $this->component->default );
				return true;
			case "textarea" :
				$this->element = new \tian\ui\base\input\textarea ( $this->component->name, $this->component->default );
				return true;
		}
		return false;
	}
	/**
	 *
	 * @return boolean
	 */
	private function defMatch() {
		switch ($this->component->dataType) {
			case "tinyint" :
			case "smallint" :
			case "int" :
			case "int" :
			case "decimal" :
			case "mediumint" :
			case "float" :
			case "double" :
			case "tinyblob" :
			case "varchar" :
			case "char" :
			case "binary" :
			case "varbinary" :
			case "time" :
			case "year" :
				$this->element = new \tian\ui\base\input\text ( $this->component->name, $this->component->default );
				return true;
			case "tinytext" :
			case "blob" :
			case "mediumblob" :
			case "mediumtext" :
			case "longblob" :
			case "longtext" :
			case "text" :
				$this->element = new \tian\ui\base\input\textarea ( $this->component->name, $this->component->default );
				return true;
			case "datetime" :
			case "timestamp" :
				$this->element = new \tian\ui\base\input\datetime ( $this->component->name, $this->component->default );
				return true;
			case "date" :
				$this->element = new \tian\ui\base\input\date ( $this->component->name, $this->component->default );
				return true;
			case "enum" :
				$this->element = new \tian\ui\base\input\select ( $this->component->name, $this->component->domain, $this->component->default );
				return true;
			case "set" :
				$this->element = new \tian\ui\base\input\checkboxGrp ( $this->component->name, $this->component->domain, $this->component->default );
				return true;
		}
		return false;
	}
}