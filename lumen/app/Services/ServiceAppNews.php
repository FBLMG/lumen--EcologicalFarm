<?php

namespace App\Services;
use App\Manages\ManageNews;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 业务 前端新闻类
 * User: Administrator
 * Date: 2017/9/04
 * Time: 16:06
 */
class ServiceAppNews extends BaseService
{
	static $errorArray = array(
		'1001' => '新闻ID不能为空',

		'2001' => '新闻获取失败',
	);

	/**
	 * 获取新闻
	 * @return array
	 */
	static function newsGet($id=0)
	{
		if(empty($id)){   //新闻ID为空返回错误
			return self::returnError('1001', self::$errorArray['1001']);
		}
		$data = self::arObject2Array(ManageNews::get($id));   //查询不到新闻返回错误
		$data['createAt']=date("Y-m-d", $data['createAt']);   //时间戳转换为日期
		return self::returnSuccess($data);
	}

	/**
	 * 获取新闻列表
	 * @param $nextId
	 * @param $pageSize
	 * @return array
	 */
	static function newsGetList($nextId, $pageSize)
	{
		// 获取缓存
		$cacheKey = self::getCacheKey(__CLASS__ . '.' . __FUNCTION__, $nextId . '_' . $pageSize);
		$cacheResult = Cache::get($cacheKey);
		if ($cacheResult) {
			return self::returnSuccess($cacheResult);
		}
		//
		$dataList = self::arObjects2Array(ManageNews::getListWithNews($nextId, $pageSize));
		foreach ($dataList as $key => $value) {
			$content = strip_tags($value['content']);  //过滤掉所有的HTML标签
			$dataList[$key]['content']=$content;       //将过滤掉后的HTML标签重新赋回去
		}
		// 设置缓存
		Cache::put($cacheKey, $dataList, self::getCacheTimeout(__CLASS__ . '.' . __FUNCTION__));
		//
		return self::returnSuccess($dataList);
	}
}