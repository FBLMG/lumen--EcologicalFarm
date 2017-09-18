<?php

namespace App\Component;

use Illuminate\Support\Facades\Log;

/**
 * 文件
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentFile
{
	/**
	 * 保存文件
	 * @param $filePath
	 * @param $base64Data
	 * @return bool
	 */
	static function saveFile($filePath, $base64Data)
	{
		try {
			if (is_file($filePath)) {
				Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $filePath（文件已存在）: ' . $filePath);
				return true;
			}
			if (!is_dir(dirname($filePath))) {
				mkdir(dirname($filePath), 0755, true);
			}
			if (file_put_contents($filePath, base64_decode($base64Data))) {
				Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $filePath: ' . $filePath);
				return true;
			} else {
				Log::error(__CLASS__ . '->' . __FUNCTION__ . ': 保存文件失败');
				Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $filePath: ' . $filePath);
				return false;
			}
		} catch (Exception $e) {
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': Exception: ' . $e->getMessage());
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $filePath: ' . $filePath);
			return false;
		}
	}

	/**
	 * 文件路径（根据DATA获取）
	 * @param $fileData
	 * @param $fileSuffix
	 * @return string
	 */
	static function filePathWithData($fileData, $fileSuffix)
	{
		return $_ENV['params']['apiUploadFilePath'] . date('Ymd') . '/' . md5($fileData) . '.' . $fileSuffix;
	}
}