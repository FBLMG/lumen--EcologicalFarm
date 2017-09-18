<?php

namespace App\Component;

/**
 * 字符串
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentString
{
	/**
	 * 将下划线命名转换为驼峰式命名
	 * @param $str
	 * @param bool $ucfirst
	 * @return mixed|string
	 */
	static function convertUnderline($str, $ucfirst = false)
	{
		$str = ucwords(str_replace('_', ' ', $str));
		$str = str_replace(' ', '', lcfirst($str));
		return $ucfirst ? ucfirst($str) : $str;
	}

	/**
	 * 汉字转数字
	 * @param $str
	 * @return mixed|string
	 */
	static function hanzi2num($str)
	{
		$numarr = self::__numDatabase();
		$hanziarr = self::__hzDatabase();
		$rstr = '';
		foreach ($hanziarr as $k => $v) {
			if (strpos($str, $v) !== false) {
				$rstr = str_replace($v, $numarr[$k], $str);
			}
		}
		return $rstr;
	}

	static function __numDatabase()
	{
		$numarr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 92, 93, 94, 95, 96, 97, 98, 99, 100);
		return $numarr;
	}

	static function __hzDatabase()
	{
		$hanziarr = array("一", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "十二", "十三", "十四", "十五", "十六", "十七", "十八", "十九", "二十", "二十一", "二十二", "二十三", "二十四", "二十五", "二十六", "二十七", "二十八", "二十九", "三十", "三十一", "三十二", "三十三", "三十四", "三十五", "三十六", "三十七", "三十八", "三十九", "四十", "四十一", "四十二", "四十三", "四十四", "四十五", "四十六", "四十七", "四十八", "四十九", "五十", "五十一", "五十二", "五十三", "五十四", "五十五", "五十六", "五十七", "五十八", "五十九", "六十", "六十一", "六十二", "六十三", "六十四", "六十五", "六十六", "六十七", "六十八", "六十九", "七十", "七十一", "七十二", "七十三", "七十四", "七十五", "七十六", "七十七", "七十八", "七十九", "八十", "八十一", "八十二", "八十三", "八十四", "八十五", "八十六", "八十七", "八十八", "八十九", "九十", "九十一", "九十二", "九十三", "九十四", "九十五", "九十六", "九十七", "九十八", "九十九", "一百");
		return $hanziarr;
	}
}