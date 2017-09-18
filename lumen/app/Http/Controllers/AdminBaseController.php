<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use App\Services\ServiceAdminIdentity;
use App\Component\ComponentFile;
use App\Component\ComponentImage;
use App\Component\ComponentAliyun;

/**
 * 后台基础API
 * Class AdminBaseController
 * @package App\Http\Controllers
 */
class AdminBaseController extends Controller
{
	private $admin = null;
	private $adminId = 0;
	private $adminToken = '';
	private $adminPower = 0;
	private $adminIsLogin = false;

	public function __construct()
	{
		parent::__construct();
		$actions = explode('/', $this->getRequestUri());
		$action = $actions[count($actions) - 1];
		if ($action === 'login' || $action ==='uploadFileForm') {
			// 不需要身份验证的API
		} else {
			$this->adminId = $this->request('adminId', $_ENV['params']['apiAdminTestId']);
			$this->adminToken = $this->request('adminToken');
			$this->admin = ServiceAdminIdentity::isLogin($this->adminId, $this->adminToken, $_ENV['params']['apiAdminTestId']);
			$this->adminPower = intval($this->admin['power']);
			$this->adminIsLogin = $this->admin === false ? false : true;
			if (!$this->adminIsLogin) {
				$this->echoError(1001, '用户未登录');
				exit;
			}
			if ($this->adminPower > 1 && !in_array($action, $_ENV['params']['adminPower'][$this->adminPower])) {
				$this->echoError(1002, '用户权限不够');
				exit;
			}
		}
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 身份相关

	/**
	 * 身份相关
	 */

	/**
	 * 登录
	 * http://localhost/admin/login?username=superadmin&password=tuxing
	 */
	public function login()
	{
		$this->echoReturn(ServiceAdminIdentity::login($this->request('username'), $this->request('password')));
	}
    

    /**
	 * 登录信息
	 * http://localhost/admin/loginInfo
	 */
	public function loginInfo()
	{
		$this->echoSuccess($this->admin);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 文件处理

	/**
	 * 文件处理
	 */

	/**
	 * 上传文件
	 * http://localhost/admin/uploadFile?fileType=1（1：大图 2：中图 3：小图）&fileData=/9j/4AAQSkZVKlQYD/9k=&fileSuffix=jpg
	 */
    public function uploadFile()
	{
		$fileType = $this->request('fileType', 0);
		$fileData = $this->request('fileData');
		$fileSuffix = $this->request('fileSuffix');
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);
		// 数据检查
		if (empty($fileData) || empty($fileSuffix)) {
			$this->echoError(1900, '请上传图片类型的文件');
		}
		// 保存文件
		try {
			if (ComponentFile::saveFile($filePath, $fileData)) {
				if (ComponentImage::isImage($filePath)) {
					// 切图
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
	}
	/**
	 * 上传文件（百度）
	 * http://localhost/admin/uploadFileForm
	 */
	public function uploadFileForm()
	{
		$fileType = 1;
		$fileData = base64_encode(file_get_contents($_FILES['ImageFile']['tmp_name']));
		$fileSuffix = substr($_FILES['ImageFile']['name'], strrpos($_FILES['ImageFile']['name'], '.') + 1);
		$filePath = ComponentFile::filePathWithData($fileData, $fileSuffix);
		// 数据检查
		if (empty($fileData) || empty($fileSuffix)) {
			$this->echoReturn(array('state' => '请上传图片类型的文件'));
		}
		// 保存文件
		try {
			if (ComponentFile::saveFile($filePath, $fileData)) {
				if ($fileType === 1) { // 上传图片类型
					if (ComponentImage::isImage($filePath)) {
						// 切图
						$resizeToSizeArray = array(
							array(),
							array(
								array(640, 0)
							), // 百度UE
						);
						$resizeToFilePathArray = array($filePath);
						foreach ($resizeToSizeArray[$fileType] as $item) {
							$resizeToFilePathArray[] = ComponentImage::resizeTo($filePath, $item[0], $item[1]);
						}
						// 切图结果检查
						foreach ($resizeToFilePathArray as $item) {
							if ($item === false) {
								$this->echoError(1011, '图片处理失败，请重试');
								return;
							}
						}
						// Result
						$resultImage = $_ENV['params']['apiDomain'] . $_ENV['params']['apiUploadFileUrl'] . date('Ymd') . '/' . basename($filePath);
						$this->echoReturn(array(
							'state' => 'SUCCESS',
							'url' => substr($resultImage, 0, strrpos($resultImage, '.')) . '_640x0' . substr($resultImage, strrpos($resultImage, '.'))
						));
					} else {
						unlink($filePath);
						$this->echoReturn(array('state' => '请上传图片类型的文件'));
					}
				}
			}
		} catch (\Exception $e) {
			unlink($filePath);
			$this->echoError(1900, '请上传图片类型的文件');
		}
	}
}
