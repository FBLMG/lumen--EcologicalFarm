<?php

namespace App\Http\Controllers;

use App\Services\ServiceCommonWeixin;
use App\Services\ServiceAppIdentity;
use App\Component\ComponentWeixin;
use Illuminate\Support\Facades\Log;

/**
 * 微信API
 * User: Administrator
 * Date: 2016/7/15
 * Time: 17:56
 */
class WxController extends Controller
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 微信

	/**
	 * 微信
	 */

	/**
	 * 身份认证
	 * http://localhost/wx/auth?url=http://www.baidu.com（授权后返回的URL）
	 */
	public function auth()
	{
		$url = $this->get('url');
		$scope = $this->get('scope', 'snsapi_base');
		$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
		if ($scope === 'snsapi_userinfo' && $_ENV['params']['weixinAllowSnsapiUserinfo']) {
			$redirectUri = $baseUrl . '?r=sc/loginSnsapiUserinfo&url=' . $url;
		} else {
			$redirectUri = $baseUrl . '?r=sc/loginSnsapiBase&url=' . $url;
		}
		$authorizeUrl = ComponentWeixin::getAuthorizeUrl(urlencode($redirectUri), $scope);
		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $redirectUri：' . $redirectUri);
		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $authorizeUrl：' . $authorizeUrl);
		header('location:' . $authorizeUrl);
	}

	/**
	 * 微信登录-SnsapiBase（前端无需调用）
	 * http://localhost/wx/loginSnsapiBase?code=2432342&url=http://www.baidu.com（授权后返回的URL）
	 */
	public function loginSnsapiBase()
	{
		$url = $this->get('url');
		$code = $this->get('code');
		// 检查code参数
		if (empty($code)) {
			header('location:' . $url);
			return;
		}
		// 获取AccessToken和OpenId
		$wxAccessTokenAndOpenId = ComponentWeixin::getAccessTokenAndOpenId($code);
		if (isset($wxAccessTokenAndOpenId['errcode']) && $wxAccessTokenAndOpenId['errcode']) {
			header('location:' . $url);
			return;
		}
		// 获取用户信息
		$wxUserInfo = ComponentWeixin::getUserInfo(
			ServiceCommonWeixin::getAccessToken(),
			$wxAccessTokenAndOpenId['openid']
		);
		if (isset($wxUserInfo['errcode']) && $wxUserInfo['errcode']) {
			header('location:' . $url);
			return;
		}
		// 登录
		if ($wxUserInfo['subscribe'] == 1) { // 用户已关注公众号
			$loginResult = ServiceAppIdentity::loginWx(
				$wxAccessTokenAndOpenId['unionid'],
				$wxAccessTokenAndOpenId['openid'], 1,
				$wxUserInfo['nickname'],
				$wxUserInfo['headimgurl'],
				$wxUserInfo['sex'] == 0 ? 3 : $wxUserInfo['sex']
			);
		} else { // 用户未关注公众号
			if ($_ENV['params']['weixinAllowSnsapiUserinfo']) { // 允许snsapi_userinfo方式获取用户信息
				$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
				$redirectUri = $baseUrl . '?r=sc/auth&scope=snsapi_userinfo&url=' . $url;
				header('location:' . $redirectUri);
				return;
			} else {
				$loginResult = ServiceAppIdentity::loginWx($wxAccessTokenAndOpenId['unionid'], $wxAccessTokenAndOpenId['openid'], 2);
			}
		}
		if ($loginResult) { // 登录成功
			setcookie('__userId__', $loginResult['id'], null, '/', $_SERVER['HTTP_HOST']);
			setcookie('__userToken__', $loginResult['token'], null, '/', $_SERVER['HTTP_HOST']);
			setcookie('__userIsNew__', $loginResult['isNew'], null, '/', $_SERVER['HTTP_HOST']);
			setcookie('__userId__', $loginResult['id'], null, '/', '.' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT']);
			setcookie('__userToken__', $loginResult['token'], null, '/', '.' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT']);
			setcookie('__userIsNew__', $loginResult['isNew'], null, '/', '.' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT']);
		}
		header('location:' . $url);
	}

	/**
	 * 微信登录-SnsapiUserinfo（前端无需调用）
	 * http://localhost/wx/loginSnsapiUserinfo?code=2432342&url=http://www.baidu.com（授权后返回的URL）
	 */
	public function loginSnsapiUserinfo()
	{
		$url = $this->get('url');
		$code = $this->get('code');
		// 检查code参数
		if (empty($code)) {
			header('location:' . $url);
			return;
		}
		// 获取AccessToken和OpenId
		$wxAccessTokenAndOpenId = ComponentWeixin::getAccessTokenAndOpenId($code);
		if (isset($wxAccessTokenAndOpenId['errcode']) && $wxAccessTokenAndOpenId['errcode']) {
			header('location:' . $url);
			return;
		}
		// 获取用户信息
		$wxUserInfo = ComponentWeixin::getSNSUserInfo(
			$wxAccessTokenAndOpenId['access_token'],
			$wxAccessTokenAndOpenId['openid']
		);
		if (isset($wxUserInfo['errcode']) && $wxUserInfo['errcode']) {
			header('location:' . $url);
			return;
		}
		// 登录
		$loginResult = ServiceAppIdentity::loginWx(
			$wxAccessTokenAndOpenId['unionid'],
			$wxAccessTokenAndOpenId['openid'], 2,
			$wxUserInfo['nickname'],
			$wxUserInfo['headimgurl'],
			$wxUserInfo['sex'] == 0 ? 3 : $wxUserInfo['sex']
		);
		if ($loginResult) { // 登录成功
			setcookie('__userId__', $loginResult['id'], null, '/', $_SERVER['HTTP_HOST']);
			setcookie('__userToken__', $loginResult['token'], null, '/', $_SERVER['HTTP_HOST']);
			setcookie('__userIsNew__', $loginResult['isNew'], null, '/', $_SERVER['HTTP_HOST']);
			setcookie('__userId__', $loginResult['id'], null, '/', '.' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT']);
			setcookie('__userToken__', $loginResult['token'], null, '/', '.' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT']);
			setcookie('__userIsNew__', $loginResult['isNew'], null, '/', '.' . $_SERVER['HTTP_HOST'] . ':' . $_SERVER['SERVER_PORT']);
		}
		header('location:' . $url);
	}

	/**
	 * 注册JsApi
	 * http://localhost/wx/jsApi?url=http://www.baidu.com（需要调用JsApi的URL）
	 */
	public function jsApi()
	{
		$url = $this->request('url');
		$jsapiTicket = ServiceCommonWeixin::getJsapiTicket();
		$jsapiSignature = ServiceCommonWeixin::getJsapiSignature($url, $jsapiTicket);
		if ($jsapiTicket && $jsapiSignature) {
			$this->echoSuccess(array(
				'appId' => $_ENV['params']['weixinAppId'],
				'nonceStr' => $jsapiSignature['noncestr'],
				'timestamp' => $jsapiSignature['time'],
				'signature' => $jsapiSignature['signature']
			));
		} else {
			$this->echoError(1002, 'JSAPI签名失败');
		}
	}

}


