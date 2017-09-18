<?php

namespace App\Http\Controllers;

use App\Services\ServiceAppIdentity;
use App\Services\ServiceAppWeixin;
use App\Component\ComponentFile;
use App\Component\ComponentWeixin;
use App\Component\ComponentImage;
use Illuminate\Support\Facades\Log;

/**
 * 前端API
 * User: Administrator
 * Date: 2016/7/15
 * Time: 17:56
 */
class AppBaseController extends Controller
{
	protected $user = null;
	protected $userId = 0;
	protected $userToken = '';
	protected $userIsLogin = false;

	public function __construct()
	{
		parent::__construct();
		$actions = explode('/', $this->getRequestUri());
		$action = $actions[count($actions) - 1];
        Log::info('action: ' . $action . ' ----------------------------------------------------------------------------------');
		if ($action === 'wxLogin') {
			// 不需要身份验证的API
		} else {
			$this->userId = $this->request('userId', $_ENV['params']['apiAppTestId']);
			$this->userToken = $this->request('userToken');
			$this->user = ServiceAppIdentity::isLogin($this->userId, $this->userToken, $_ENV['params']['apiAppTestId']);
			$this->userIsLogin = $this->user === false ? false : true;
			if (!$this->userIsLogin) {
				$this->echoError(1001, '用户未登录');
				exit;
			}
		}
	}

	/**
	 * 用户信息
	 * http://localhost/app/userinfo
	 */
	public function userinfo()
	{
		$this->echoSuccess($this->user);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 微信
    

    /**
	 * 微信登陆
	 * http://localhost/app/wxLogin?code=325432432（登录时获取的 code）&encryptedData=encryptedData（敏感数据）&iv=iv（加密算法的初始向量）
	 */
    public function wxLogin()
	{
		$code = $this->request('code', '');
		$encryptedData = $this->request('encryptedData', '');
		$iv = $this->request('iv', '');
		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $code：' . $code);
		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $encryptedData：' . $encryptedData);
		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $iv：' . $iv);
		// 检查code参数
		if (empty($code)) {
			$this->echoError(1002, '用户登录失败');
			exit;
		}
		// 获取SessionKey和OpenId
		$wxAccessSessionKeyAndOpenId = ComponentWeixin::getAccessSessionKeyAndOpenId($code);
		if (isset($wxAccessSessionKeyAndOpenId['errcode']) && $wxAccessSessionKeyAndOpenId['errcode']) {
			$this->echoError(1002, '用户登录失败');
			exit;
		}
		// 解析用户信息
		$userinfo = ServiceAppWeixin::parseUserInfo($_ENV['params']['weixinAppId_XCX'], $wxAccessSessionKeyAndOpenId['session_key'], $encryptedData, $iv);
		if (empty($userinfo)) {
			$this->echoError(1002, '用户登录失败');
			exit;
		}
		//登陆本地
		$loginResult = ServiceAppIdentity::login(
			$userinfo->openId,
			$userinfo->nickName,
			$userinfo->avatarUrl,
			$userinfo->gender == 0 ? 3 : $userinfo->gender
		);
		$this->echoSuccess(array(
			'id' => $loginResult['id'],
			'token' => $loginResult['token']
		));
	} 

	/**
	 * 上传图片文件（微信小程序）
     * http://localhost/app/uploadImageWithWx?ImageFile=FILE（对象）
	 */
	public function uploadImageWithWx(){
		$fileType = 0;
		$fileData = base64_encode(file_get_contents($_FILES['ImageFile']['tmp_name']));
		$fileSuffix = substr($_FILES['ImageFile']['name'], strrpos($_FILES['ImageFile']['name'], '.') + 1);
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);	
		// 数据检查
		if (empty($fileData) || empty($fileSuffix)) {
			$this->echoReturn('0|请上传图片类型的文件');
		}
		// 保存文件

        try {
          if (ComponentFile::saveFile($filePath, $fileData)) {
        	if (ComponentImage::isImage($filePath)) {// 切图
				$resizeToSizeArray = array(
					array(
						array(56, 56)
					)
				);
				$resizeToFilePathArray = array($filePath);
				foreach ($resizeToSizeArray[$fileType] as $item) {
					$resizeToFilePathArray[] = ComponentImage::resizeTo($filePath, $item[0], $item[1]);
				}
				// 切图结果检查
				foreach ($resizeToFilePathArray as $item) {
					if ($item === false) {
						$this->echoError(1901, '图片处理失败，请重试');
						return;
					}
				}
				// Result
				$this->echoSuccess($_ENV['params']['apiUploadFileUrl'] . date('Ymd') . '/' . basename($filePath));
			} else {
				unlink($filePath);
				$this->echoError(1900, '请上传图片类型的文件');
			}
          }

        } catch (\Exception $e) {
			unlink($filePath);
			$this->echoError(1900, '请上传图片类型的文件');
		}
        //
	}

   	/**
	 * 上传视频文件（微信小程序）
     * http://localhost/app/uploadVideoWithWx?VideoFile=FILE（对象）
	 */
   	public function uploadVideoWithWx(){
		$fileData = base64_encode(file_get_contents($_FILES['VideoFile']['tmp_name']));
		$fileSuffix = substr($_FILES['VideoFile']['name'], strrpos($_FILES['VideoFile']['name'], '.') + 1);
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);
		// 数据检查
		if (empty($fileData) || empty($fileSuffix)) {
			$this->echoReturn('0|请上传视频类型的文件');
		}
		try{
            if (ComponentFile::saveFile($filePath, $fileData)) {     
				$this->echoSuccess($_ENV['params']['apiUploadFileUrl'] . date('Ymd') . '/' . basename($filePath));
            }else{
            	unlink($filePath);
				$this->echoError(1900, '请上传视频类型的文件');
            }
		}catch (\Exception $e) {
			unlink($filePath);
			$this->echoError(1900, '请上传视频类型的文件');
		}
   	}
}


