<?php

namespace App\Component;

/**
 * 日期时间
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentDate
{
	/**
	 * 输出带毫秒的时间
	 * @param string $format
	 * @param null $utimestamp
	 * @return bool|string
	 */
	static function udate($format = 'u', $utimestamp = null)
	{
		if (is_null($utimestamp)) {
			$utimestamp = microtime(true);
		}
		$timestamp = floor($utimestamp);
		$milliseconds = round(($utimestamp - $timestamp) * 1000000);
		return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
	}
}