<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Manages\ManageAdmin;
use App\Component\ComponentRandom;

/**
 * 业务 后台身份类
 * User: Administrator
 * Date: 2016/7/13
 * Time: 10:12
 */
class ServiceAdminIdentity extends BaseService
{
	static $errorArray = array(
		2001 => '用户不存在',
		2002 => '密码错误',
		2003 => '登录失败',
		2004 => '状态被禁用,登陆失败',
	);

	/**
	 * 是否登录
	 * @param $adminId
	 * @param $adminToken
	 * @param int $adminTestId
	 * @return bool|mixed
	 */
	static function isLogin($adminId, $adminToken, $adminTestId = 0)
	{
		$admin = self::arObject2Array(ManageAdmin::get($adminId));
		if (empty($admin)) {
			return false;
		}
		if ($admin['token'] === $adminToken && $admin['tokenOverAt'] > time()) {
			return $admin;
		}
		if ($adminTestId && $adminTestId === intval($admin['id'])) {
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': 当前用户为测试用户，ID（' . $adminTestId . '）');
			return $admin;
		}
		return false;
	}

	/**
	 * 登录
	 * @param $username
	 * @param $password
	 * @return array
	 */
	static function login($username, $password)
	{
		$admin = self::arObject2Array(ManageAdmin::getWith('username', $username));
		if (empty($admin)) {
			return self::returnError(2001, self::$errorArray[2001]);
		}
		if ($admin['password'] !== self::encryptPassword($password)) {
			return self::returnError(2002, self::$errorArray[2002]);
		}
		if($admin['status']!='1'){
            return self::returnError(2004, self::$errorArray[2004]);
		}
		// 更新Token
		$adminId = $admin['id'];
		$adminToken = self::createToken($adminId);
		$adminTokenOverTime = time() + $_ENV['params']['apiAdminTokenOverTime'];
		$result = ManageAdmin::updateToken($adminId, $adminToken, $adminTokenOverTime);
		if (empty($result)) {
			return self::returnError(2003, self::$errorArray[2003]);
		}
		// 管理员权限
		$admin['powerList'] = isset($_ENV['params']['adminPower'][$admin['power']]) ? $_ENV['params']['adminPower'][$admin['power']] : array();
		// 修复$admin的Token信息
		$admin['token'] = $adminToken;
		$admin['tokenOverAt'] = $adminTokenOverTime;
		$admin['tokenLastAt'] = time();
		return self::returnSuccess($admin);
	}

	/**
	 * 密码加密
	 * @param $password
	 * @return string
	 */
	static function encryptPassword($password)
	{
		return md5('fdgg&&^dfdsg' . $password . 'jhd&&*GHFsdfhg');
	}

	/**
	 * 创建Token
	 * @param $adminId
	 * @return string
	 */
	static function createToken($adminId)
	{
		return md5($adminId . ComponentRandom::genRandomStr(32));
	}
}