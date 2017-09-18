<?php

namespace App\Services;

use App\Component\ComponentString;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

/**
 * 业务 基础类
 * User: Administrator
 * Date: 2016/7/13
 * Time: 10:30
 */
class BaseService
{

	/**
	 * 返回成功
	 * @param array $data
	 * @return array
	 */
	static function returnSuccess($data = array())
	{
		return array(
			'code' => 0,
			'data' => $data
		);
	}

	/**
	 * 返回错误
	 * @param $code
	 * @param $message
	 * @return array
	 */
	static function returnError($code, $message)
	{
		return array(
			'code'    => $code,
			'message' => $message
		);
	}

	/**
	 * AR对象转换为数组
	 * @param $arObject
	 * @return mixed
	 */
	static function arObject2Array($arObject)
	{
		if ($arObject && is_object($arObject)) {
			$result = array();
			foreach ($arObject as $key => $item) {
				$result[ComponentString::convertUnderline($key)] = $item;
			}
			return $result;
		} else {
			return null;
		}
	}

	/**
	 * AR对象数组转换为二维数组
	 * @param $arObjects
	 * @return array
	 */
	static function arObjects2Array($arObjects)
	{
		$result = array();
		foreach ($arObjects as $item) {
			$result[] = self::arObject2Array($item);
		}
		return $result;
	}

	/**
	 * 获取缓存的KEY
	 * @param $key
	 * @param $other
	 * @return string
	 */
	static function getCacheKey($key, $other)
	{
		Log::info(__CLASS__ . '->' . __FUNCTION__ . '.$cacheKey: ' . $key . '_' . $other);
		return $key . '_' . $other;
	}

	/**
	 * 获取缓存的超时时间
	 * @param $key
	 * @param $default
	 * @return mixed
	 */
	static function getCacheTimeout($key, $default = 0)
	{
		if (isset($_ENV['params']['cacheKeys'][$key])) {
			return $_ENV['params']['cacheKeys'][$key];
		} else {
			return $default ? $default : 30;
		}
	}

	/**
	 * 结果是否为空
	 * @param $result
	 * @return bool
	 */
	static function resultEmpty($result)
	{
		if (is_numeric($result)) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 对象转换为数组
	 * @param $object
	 * @return array
	 */
	static function object2Array($object)
	{
		if (is_object($object) || is_array($object)) {
			$array = array();
			foreach ($object as $key => $item) {
				if (is_object($item)) {
					$array[$key] = self::object2Array($item);
				} else {
					$array[$key] = $item;
				}
			}
			return $array;
		}
		return array();
	}
}