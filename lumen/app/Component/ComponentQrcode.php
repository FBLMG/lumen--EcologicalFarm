<?php

namespace App\Component;

require_once dirname(dirname(__FILE__)) . '/Thirdparty/phpqrcode/qrlib.php';

/**
 * 图片处理
 * User: Administrator
 * Date: 2016/7/13
 * Time: 16:44
 */
class ComponentQrcode
{
	/**
	 * 转换二维码文件
	 * @param $string
	 * @return string
	 */
	static function toQrcodePngFile($string)
	{
		$filename = ComponentFile::filePathWithData($string, 'png');
		if (!is_dir(dirname($filename))) {
			mkdir(dirname($filename), 755);
		}
		\QRcode::png($string, $filename, 'L', 4, 2);
		return $filename;
	}

	/**
	 * 转换二维码Base64数据
	 * @param $string
	 * @return string
	 */
	static function toQrcodePngBase64($string)
	{
		return base64_encode(file_get_contents(self::toQrcodePngFile($string)));
	}
}