<?php

/**
 * @Author: awei.tian
 * @Date: 2016年9月5日
 * @Desc: 
 * 	MySQL支持大量的列类型，它可以被分为3类：
 * 		数字类型、
 * 		日期和时间类型以及
 * 		字符串(字符)类型
 * 
 * 
 * 		MYSQL字段的域有
 * 			tinyint,	smallint,	int,		bigint
 * 			float,		double,		decimal,	mediumint
 * 			text,		tinyblob,	tinytext,	blob
 * 			mediumblob,	mediumtext,	longblob,	longtext
 *			datetime,	timestamp,	date,		time
 *			year,		enum,		set,		varchar
 *			char,		binary,		varbinary
 * 依赖:
 */
namespace tian\mysql;

class field extends \tian\data\component {
	public function domainChk($value) {
		switch ($this->dataType) {
			case "tinyint" :
			case "smallint" :
			case "int" :
			case "int" :
			case "decimal" :
			case "mediumint" :
				if ($this->unsiged) {
					return \tian\validator::isUint ( $value );
				} else {
					return \tian\validator::isInt ( $value );
				}
			case "float" :
			case "double" :
				return \tian\validator::isFloat ( $value );
			case "text" :
			case "tinyblob" :
			case "tinytext" :
			case "blob" :
			case "mediumblob" :
			case "mediumtext" :
			case "longblob" :
			case "longtext" :
			case "varchar" :
			case "char" :
			case "binary" :
			case "varbinary" :
				if ($this->allowNull)
					return true;
				else
					return strlen ( $value ) > 0;
			case "datetime" :
			case "timestamp" :
				return \tian\validator::isDateTime ( $value );
			case "time" :
				return \tian\validator::isTime ( $value );
			case "date" :
				return \tian\validator::isDate ( $value );
			case "year" :
				return \tian\validator::isYear ( $value );
			case "enum" :
				if (! is_array ( $this->domain ))
					return false;
				return in_array ( $value, $this->domain );
			case "set" :
				if (! is_array ( $this->domain ))
					return false;
				if (! is_array ( $value ))
					return in_array ( $value, $this->domain );
				else {
					$ret = true;
					foreach ( $value as $item ) {
						if (! in_array ( $item, $this->domain )) {
							$ret = false;
							break;
						}
					}
					return $ret;
				}
		}
		return false;
	}
}