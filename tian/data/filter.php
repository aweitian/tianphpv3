<?php

/**
 * @Author: awei.tian
 * @Date: 2016年8月4日
 * @Desc: 
 * 依赖:
 */
namespace tian\data;

class filter {
	/**
	 * 用户数据输出时过滤
	 *
	 * @param string $html        	
	 * @return string
	 */
	public static function filterOut($html) {
		return htmlspecialchars ( $html, ENT_QUOTES );
	}
}