<?php
namespace App\Services;

require_once dirname(dirname(__FILE__)) . '/Thirdparty/aes-sample/wxBizDataCrypt.php';

/**
 * 业务 前台微信类
 * User: Administrator
 * Date: 2016/7/13
 * Time: 10:12
 */
class ServiceAppWeixin extends BaseService
{
	/**
	 * 解析用户信息
	 * @param $appId
	 * @param $sessionKey
	 * @param $encryptedData
	 * @param $iv
	 * @return bool|mixed
	 */
	static function parseUserInfo($appId, $sessionKey, $encryptedData, $iv)
	{
		$pc = new \WXBizDataCrypt($appId, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $data);
		if ($errCode == 0) {
			return json_decode($data);
		} else {
			return false;
		}
	}

}

