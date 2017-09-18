<?php

namespace App\Component;

use Illuminate\Support\Facades\Log;

require_once dirname(dirname(__FILE__)) . '/Thirdparty/aliyun-oss-php-sdk-master/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

/**
 * 阿里云
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentAliyun
{
	/**
	 * 获取OSSClient实例
	 * @param $accessKeyId
	 * @param $accessKeySecret
	 * @param $endpoint
	 * @return null|OssClient
	 */
	static function getOssClient($accessKeyId, $accessKeySecret, $endpoint)
	{
		try {
			$ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint, false);
		} catch (OssException $e) {
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': Exception: ' . $e->getMessage());
			return null;
		}
		return $ossClient;
	}

	/**
	 * 上传指定的本地文件内容（根据OSSClient实例、存储空间名称、Object名字）
	 * @param OssClient $ossClient OSSClient实例
	 * @param string $bucket 存储空间名称
	 * @param string $object Object名字
	 * @param string $filePath 上传的文件地址
	 * @return bool
	 */
	static function uploadFileWithOssClient($ossClient, $bucket, $object, $filePath)
	{
		if (empty($ossClient)) {
			return false;
		}
		try {
			$ossClient->uploadFile($bucket, $object, $filePath);
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $filePath: ' . $filePath);
			return true;
		} catch (OssException $e) {
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': Exception: ' . $e->getMessage());
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $bucket: ' . $bucket);
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $object: ' . $object);
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $filePath: ' . $filePath);
			return false;
		}
	}

	/**
	 * 上传指定的本地文件内容
	 * @param $filePath
	 * @return bool
	 */
	static function uploadFile($filePath)
	{
		$aliyunAccessKeyId = $_ENV['params']['aliyunAccessKeyId'];
		$aliyunAccessKeySecret = $_ENV['params']['aliyunAccessKeySecret'];
		$aliyunEndpoint = $_ENV['params']['aliyunEndpoint'];
		$aliyunBucket = $_ENV['params']['aliyunBucket'];
		$aliyunObject = self::objectWithFilePath($filePath);
		$ossClient = self::getOssClient($aliyunAccessKeyId, $aliyunAccessKeySecret, $aliyunEndpoint);
		return self::uploadFileWithOssClient($ossClient, $aliyunBucket, $aliyunObject, $filePath);
	}

	/**
	 * Object地址
	 * @param $filePath
	 * @return string
	 */
	static function objectWithFilePath($filePath)
	{
		return $_ENV['params']['aliyunObject'] . date('Ymd') . '/' . basename($filePath);
	}

	/**
	 * 文件的OSS可访问域名
	 * @param $filePath
	 * @return string
	 */
	static function fileDomainWithFilePath($filePath)
	{
		return $_ENV['params']['aliyunDomain'] . self::objectWithFilePath($filePath);
	}
}