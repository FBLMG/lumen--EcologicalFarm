<?php

namespace App\Component;

use Illuminate\Support\Facades\Log;

require_once dirname(dirname(__FILE__)) . '/Thirdparty/ImageImagick.php';
require_once dirname(dirname(__FILE__)) . '/Thirdparty/ThumbHandler.php';

/**
 * 图片处理
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentImage
{
	/**
	 * 判断文件是否图片
	 * @param $filePath
	 * @return bool
	 */
	static function isImage($filePath)
	{
		if (!getimagesize($filePath)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * 切图
	 * @param $filePath
	 * @param int $width
	 * @param int $height
	 * @return bool|string
	 * @throws Exception
	 */
	static function resizeTo($filePath, $width = 100, $height = 100)
	{
		if ($height) {
			return self::cutAndZoom($filePath, $width, $height);
		} else {
			return self::zoom($filePath, $width);
		}
	}

	/**
	 * 截取并缩放
	 * @param $filePath
	 * @param $width
	 * @param $height
	 * @return bool|string
	 * @throws Exception
	 */
	static function cutAndZoom($filePath, $width, $height)
	{
		$savePath = substr($filePath, 0, strrpos($filePath, '.')) . '_' . $width . 'x' . $height . substr($filePath, strrpos($filePath, '.'));
		$tempPath = substr($filePath, 0, strrpos($filePath, '.')) . '_temp' . substr($filePath, strrpos($filePath, '.'));
		try {
			// 计算截取尺寸
			$tmpImageSize = getimagesize($filePath);
			$originalImageWidth = $tmpImageSize[0];
			$originalImageHeight = $tmpImageSize[1];
			$cx = 0;    // 坐标X
			$cy = 0;    // 坐标Y
			$cw = 0;    // 截图宽
			$ch = 0;    // 截图高
			$_h = floor(($originalImageWidth * $height) / $width);
			if ($_h > $originalImageHeight) {
				$_w = floor(($originalImageHeight * $width) / $height);
				$cy = 0;
				$ch = $originalImageHeight;
				$cw = $_w;
				$cx = floor(($originalImageWidth - $cw) / 2);
			} else {
				$cx = 0;
				$cw = $originalImageWidth;
				$ch = $_h;
				$cy = floor(($originalImageHeight - $ch) / 2);
			}
			// 按尺寸截取
			$thumbHandler = new \ThumbHandler();
			$thumbHandler->img_display_quality = 100;
			$thumbHandler->setSrcImg($filePath);
			$thumbHandler->setDstImg($tempPath);
			$thumbHandler->setCutType(2);
			$thumbHandler->setSrcCutPosition($cx, $cy); // 源图起点坐标
			$thumbHandler->setRectangleCut($cw, $ch); // 裁切尺寸
			$thumbHandler->createImg($width, $height); // 指定缩放比例
			unset($thumbHandler);

			// 等比缩放
			$thumbHandler = new \ThumbHandler();
			$thumbHandler->img_display_quality = 85;
			$thumbHandler->setSrcImg($tempPath);
			$thumbHandler->setDstImg($savePath);
			$thumbHandler->setCutType(0);
			$thumbHandler->createImg($width, $height); // 指定缩放比例
			unset($thumbHandler);

			// 删除临时文件
			unlink($tempPath);

			// ResultZ
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $savePath: ' . $savePath);
			return $savePath;
		} catch (Exception $e) {
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': Exception: ' . $e->getMessage());
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $filePath: ' . $filePath);
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $savePath: ' . $savePath);
			return false;
		}
	}

	/**
	 * 缩放（根据宽度）
	 * @param $filePath
	 * @param $width
	 * @return bool|string
	 * @throws C
	 */
	static function zoom($filePath, $width)
	{
		$savePath = substr($filePath, 0, strrpos($filePath, '.')) . '_' . $width . 'x0' . substr($filePath, strrpos($filePath, '.'));
		try {
			// 计算截取尺寸
			$tmpImageSize = getimagesize($filePath);
			$originalImageWidth = $tmpImageSize[0];
			$originalImageHeight = $tmpImageSize[1];
			$targetHeight = $originalImageHeight * ($width / $originalImageWidth);

			// 等比缩放
			$thumbHandler = new \ThumbHandler();
			$thumbHandler->img_display_quality = 85;
			$thumbHandler->setSrcImg($filePath);
			$thumbHandler->setDstImg($savePath);
			$thumbHandler->setCutType(0);
			$thumbHandler->createImg($width, $targetHeight); // 指定缩放比例
			unset($thumbHandler);

			// Result
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': $savePath: ' . $savePath);
			return $savePath;
		} catch (Exception $e) {
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': Exception: ' . $e->getMessage());
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $filePath: ' . $filePath);
			Log::error(__CLASS__ . '->' . __FUNCTION__ . ': $savePath: ' . $savePath);
			return false;
		}
	}
}