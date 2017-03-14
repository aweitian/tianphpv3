<?php

/**
 * @Author: awei.tian
 * @Date: 2016年12月7日
 * @Desc: 
 * 依赖:
 */
namespace tian\interfaces;

interface IModule {
	/**
	 * 检测URL是否匹配，匹配后使用相应的URLPATH
	 *
	 * @return bool
	 */
	public function match($urlpath);
	/**
	 * 检查是否可抵达,并准备就绪
	 *
	 * @return bool
	 */
	public function dispatch();
	
	/**
	 * 执行任务
	 *
	 * @return void
	 */
	public function invoke();
}