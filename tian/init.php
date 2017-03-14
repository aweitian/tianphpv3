<?php

/**
 * @Author: awei.tian
 * @Date: 2016年7月16日
 * @Desc: 
 * 依赖:
 */
namespace tian;

defined ( "HTTP_RESPONSE_404_TPL" ) or define ( "HTTP_RESPONSE_404_TPL", __DIR__ . "/tpl/404.tpl.php" );
defined ( "HTTP_RESPONSE_MSG_TPL" ) or define ( "HTTP_RESPONSE_MSG_TPL", __DIR__ . "/tpl/msg.tpl.php" );

defined ( "LOG_OPEN_FLAG" ) or define ( "LOG_OPEN_FLAG", true );

// 加载不需要任何依赖的组件和日志
require_once __DIR__ . "/utils/utility.php";
require_once __DIR__ . "/log.php";
require_once __DIR__ . "/utils/httpDataConverter.php";



