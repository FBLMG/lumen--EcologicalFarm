<?php

namespace App\Component;

use App\Services\BaseService;
use App\Manages\ManageVariable;

/**
 * 变量
 * User: Administrator
 * Date: 2016/7/15
 * Time: 19:23
 */
class ComponentVariable
{
	/**
	 * 获取变量
	 * @param $name
	 * @param string $default
	 * @return string
	 */
	static function getVariable($name, $default = '')
	{
		$variable = BaseService::arObject2Array(ManageVariable::getWith('name', $name));
		if ($variable) {
			return $variable['value'];
		} else {
			return $default;
		}
	}

	/**
	 * 获取变量列表
	 * @param $group
	 * @return array
	 */
	static function getVariableList($group)
	{
		return BaseService::arObjects2Array(ManageVariable::getList($group));
	}

	/**
	 * 设置变量
	 * @param $group
	 * @param $name
	 * @param $value
	 * @param string $desc
	 * @return int
	 */
	static function setVariable($group, $name, $value, $desc = '')
	{
		$variable = BaseService::arObject2Array(ManageVariable::getWith('name', $name));
		if ($variable) {
			return ManageVariable::update($variable['id'], $group, $name, $value, $desc);
		} else {
			return ManageVariable::insert($group, $name, $value, $desc);
		}
	}
}