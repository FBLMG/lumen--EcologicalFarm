<?php

namespace App\Component;

use Illuminate\Support\Facades\Log;

/**
 * 微信
 * User: Administrator
 * Date: 2016/7/15
 * Time: 19:23
 */
class ComponentWeixin
{
	/**
	 * 对象格式参数转换为URL格式参数
	 * @param $urlObj
	 * @return string
	 */
	static function params2url($urlObj)
	{
		$buff = "";
		foreach ($urlObj as $k => $v) {
			if ($k != "sign") {
				$buff .= $k . "=" . $v . "&";
			}
		}
		$buff = trim($buff, "&");
		return $buff;
	}

	/**
	 * Get请求
	 * @param $url
	 * @param array $params
	 * @param int $timeout
	 * @return mixed
	 */
	static function curlGet($url, $params = array(), $timeout = 30)
	{
		Log::info(__CLASS__ . '->' . __FUNCTION__ . '-URL: ' . $url . '?' . self::params2url($params));
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $url . '?' . self::params2url($params));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		Log::info(__CLASS__ . '->' . __FUNCTION__ . '-RESULT: ' . $res);
		return $data;
	}

	/**
	 * Get请求
	 * @param $url
	 * @param array $params
	 * @param int $timeout
	 * @return mixed
	 */
	static function curlGet1($url, $params = array(), $timeout = 30)
	{
		Log::info(__CLASS__ . '->' . __FUNCTION__ . '-URL: ' . $url);
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		Log::info(__CLASS__ . '->' . __FUNCTION__ . '-RESULT: ' . $res);
		return $data;
	}

	/**
	 *  Post请求
	 * @param $url
	 * @param $params
	 * @param int $timeout
	 * @return mixed
	 */
	static function curlPost($url, $params, $timeout = 30)
	{
		$paramsString = json_encode($params);
		// 初始化curl
		$ch = curl_init();
		// 设置超时
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// post数据
		curl_setopt($ch, CURLOPT_POST, 1);
		// post的变量
		curl_setopt($ch, CURLOPT_POSTFIELDS, $paramsString);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Content-Length: ' . strlen($paramsString))
		);
		// 运行curl，结果以jason形式返回
		$res = curl_exec($ch);
		curl_close($ch);
		// 取出数据
		$data = json_decode($res, true);
		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': ' . $res);
		return $data;
	}

	/**
	 * 获取 AccessToken
	 * @return mixed
	 */
	static function getAccessToken()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token';
		$params = array(
			'grant_type' => 'client_credential',
			'appid' => $_ENV['params']['weixinAppId'],
			'secret' => $_ENV['params']['weixinAppSecret']
		);
		return self::curlGet($url, $params);
	}

	/**
	 * 获取 JsapiTicket
	 * @param $accessToken
	 * @return mixed
	 */
	static function getJsapiTicket($accessToken)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
		$params = array(
			'access_token' => $accessToken,
			'type' => 'jsapi'
		);
		return self::curlGet($url, $params);
	}

	/**
	 * 获取网页授权URL
	 * @param $redirectUri
	 * @param string $scope 应用授权作用域，snsapi_base （不弹出授权页面，直接跳转，只能获取用户openid），snsapi_userinfo （弹出授权页面，可通过openid拿到昵称、性别、所在地。并且，即使在未关注的情况下，只要用户授权，也能获取其信息）
	 * @param string $state
	 * @return string
	 */
	static function getAuthorizeUrl($redirectUri, $scope = 'snsapi_base', $state = '')
	{
		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize';
		$params = array(
			'appid' => $_ENV['params']['weixinAppId'],
			'redirect_uri' => $redirectUri,
			'response_type' => 'code',
			'scope' => $scope,
			'state' => $state
		);
		return $url . '?' . self::params2url($params) . '#wechat_redirect';
	}

	/**
	 * 获取AccessToken和OpenId
	 * @param $code
	 * @return mixed
	 */
	static function getAccessTokenAndOpenId($code)
	{
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
		$params = array(
			'appid' => $_ENV['params']['weixinAppId'],
			'secret' => $_ENV['params']['weixinAppSecret'],
			'code' => $code,
			'grant_type' => 'authorization_code'
		);
		return self::curlGet($url, $params);
	}

	/**
	 * 获取SessionKey和OpenId
	 * @param $code
	 * @return mixed
	 */
	static function getAccessSessionKeyAndOpenId($code)
	{
		$url = 'https://api.weixin.qq.com/sns/jscode2session';
		$params = array(
			'appid' => $_ENV['params']['weixinAppId_XCX'],
			'secret' => $_ENV['params']['weixinAppSecret_XCX'],
			'js_code' => $code,
			'grant_type' => 'authorization_code'
		);
		return self::curlGet($url, $params);
	}

	/**
	 * 获取用户信息
	 * @param $accessToken
	 * @param $openId
	 * @return mixed
	 */
	static function getUserInfo($accessToken, $openId)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info';
		$params = array(
			'access_token' => $accessToken,
			'openid' => $openId,
			'lang' => 'zh_CN'
		);
		return self::curlGet($url, $params);
	}

	/**
	 * 获取用户信息
	 * @param $accessToken string 网页授权$accessToken，它和普通的$accessToken不一样
	 * @param $openId
	 * @return mixed
	 */
	static function getSNSUserInfo($accessToken, $openId)
	{
		$url = 'https://api.weixin.qq.com/sns/userinfo';
		$params = array(
			'access_token' => $accessToken,
			'openid' => $openId,
			'lang' => 'zh_CN'
		);
		return self::curlGet($url, $params);
	}

	/**
	 * 发送开奖消息
	 * @param $accessToken
	 * @param $toOpenId
	 * @param $issueNumber
	 * @param $goodsTitle
	 * @return mixed
	 */
	static function sendWinMessage($accessToken, $toOpenId, $issueNumber, $goodsTitle)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $accessToken;
		$params = array(
			'touser' => $toOpenId,
			'template_id' => '8-E2q3Fm5XHnZCxGrRuaVCX0IXQEiHPME0aZMG6uRxU',
			'url' => $_ENV['params']['apiWebDomain'] . '#/my_duobao_record/1',
			'data' => array(
				'first' => array(
					'value' => '恭喜您参与的夺宝商品中奖了！',
					'color' => '#ff0000'
				),
				'keyword1' => array(
					'value' => $_ENV['params']['title'] . '，第 ' . $issueNumber . ' 期',
					'color' => '#173177'
				),
				'keyword2' => array(
					'value' => $goodsTitle,
					'color' => '#173177'
				),
				'remark' => array(
					'value' => '赶快点击这里收取中奖商品>',
					'color' => '#173177'
				)
			)
		);
		return self::curlPost($url, $params);
	}

	/**
	 * 发送未中奖信息
	 * @param $accessToken
	 * @param $toOpenId
	 * @param $issueId
	 * @param $issueNumber
	 * @param $goodsTitle
	 * @param $winCode
	 * @return mixed
	 */
	static function sendNotWinMessage($accessToken, $toOpenId, $issueId, $issueNumber, $goodsTitle, $winCode)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $accessToken;
		$params = array(
			'touser' => $toOpenId,
			'template_id' => '8-E2q3Fm5XHnZCxGrRuaVCX0IXQEiHPME0aZMG6uRxU',
			'url' => $_ENV['params']['apiWebDomain'] . '#/duobao_goods/' . $issueId . '/0/0',
			'data' => array(
				'first' => array(
					'value' => '您参与的商品幸运号码为' . $winCode,
					'color' => '#173177'
				),
				'keyword1' => array(
					'value' => $_ENV['params']['title'] . '，第 ' . $issueNumber . ' 期',
					'color' => '#173177'
				),
				'keyword2' => array(
					'value' => $goodsTitle,
					'color' => '#173177'
				),
				'remark' => array(
					'value' => '赶快点击这里查看详情>',
					'color' => '#173177'
				)
			)
		);
		return self::curlPost($url, $params);
	}
}