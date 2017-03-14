<?php

/**
 * @Author: awei.tian
 * @Date: 2016年12月8日
 * @Desc: 
 * 依赖:
 */
namespace tian;

class log {
	const LEVEL_DEBUG = "debug: ";
	const LEVEL_INFO = "info: ";
	const LEVEL_NOTICE = "notice: ";
	const LEVEL_WARNING = "warning: ";
	const LEVEL_ERROR = "error: ";
	private static $fp = NULL;
	private static function open() {
		if (is_null ( self::$fp )) {
			self::$fp = fopen ( "php://memory", 'r+' );
		}
	}
	public static function d($s, $line = "\n") {
		self::l ( $s, $line, log::LEVEL_DEBUG );
	}
	public static function w($s, $line = "\n") {
		self::l ( $s, $line, log::LEVEL_WARNING );
	}
	public static function e($s, $line = "\n") {
		self::l ( $s, $line, log::LEVEL_ERROR );
	}
	public static function i($s, $line = "\n") {
		self::l ( $s, $line, log::LEVEL_INFO );
	}
	private static function l($s, $line, $level) {
		if (! LOG_OPEN_FLAG)
			return;
		self::open ();
		fputs ( self::$fp, $level . $s . $line );
	}
	/**
	 *
	 * @param string $level
	 *        	//self::LEVEL_DEBUG
	 */
	public static function output($level = "ALL") {
		if (! LOG_OPEN_FLAG)
			return;
		self::open ();
		rewind ( self::$fp );
		while ( ! feof ( self::$fp ) ) {
			if ($level != "ALL") {
				$info = fread ( self::$fp, 1024 );
				if (\tian\utils\utility::startsWith ( $info, $level )) {
					echo $info;
				}
			} else {
				echo fread ( self::$fp, 1024 );
			}
		}
	}
	private function close() {
		if (! LOG_OPEN_FLAG)
			return;
		if (! is_null ( self::$fp )) {
			fclose ( self::$fp );
		}
	}
	public static function clear() {
		if (! LOG_OPEN_FLAG)
			return;
		if (! is_null ( self::$fp )) {
			rewind ( self::$fp );
		}
	}
}