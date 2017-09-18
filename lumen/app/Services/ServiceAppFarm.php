<?php

namespace App\Services;
use App\Manages\ManageFarm;
use App\Manages\ManageRegion;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 业务 前端农场类
 * User: Administrator
 * Date: 2017/9/05
 * Time: 10:06
 */
class ServiceAppFarm extends BaseService
{
	static $errorArray = array(
		'1001' => '农场ID不能为空',

		'2001' => '农场获取失败',
	);

	/**
	 * 获取农场
	 * @return array
	 */
	static function farmGet($id = 0)
	{
		if(empty($id)){   //农场ID为空返回错误
			return self::returnError('1001', self::$errorArray['1001']);
		}
		$data = self::arObject2Array(ManageFarm::get($id));   
		return self::returnSuccess($data);
	}

	/**
	 * 获取农场列表
	 * @param $regionId
	 * @param $countyId
	 * @param $nextId
	 * @param $pageSize
	 * @return array
	 */
	static function farmGetList($regionId = 0,$countyId = 0,$nextId = 0, $pageSize = 0)
	{
		// 获取缓存
		$cacheKey = self::getCacheKey(__CLASS__ . '.' . __FUNCTION__, $regionId . '_' . $countyId . '_' . $nextId . '_' . $pageSize);
		$cacheResult = Cache::get($cacheKey);
		if ($cacheResult) {
			return self::returnSuccess($cacheResult);
		}
		//
		$dataList = self::arObjects2Array(ManageFarm::getListWithFarm($regionId,$countyId,$nextId,$pageSize));
		foreach ($dataList as $key => $value) {
			$region= self::arObject2Array(ManageRegion::get($value['regionId']));   //获取上级地区
			$county= self::arObject2Array(ManageRegion::get($value['countyId']));   //获取下级地区
			$dataList[$key]['regionName']=$region['name'];
			$dataList[$key]['countyName']=$county['name'];
			$content = strip_tags($value['content']);  //过滤掉所有的HTML标签
			$dataList[$key]['content']=$content;       //将过滤掉后的HTML标签重新赋回去
		}
		// 设置缓存
		Cache::put($cacheKey, $dataList, self::getCacheTimeout(__CLASS__ . '.' . __FUNCTION__));
		//
		return self::returnSuccess($dataList);
	}

	static function regionGetList()
	{
		$wheres['region_id']='0';   //只查询父级的
		$orders = array('id' => 'DESC');
		$pageSize='0';
		$dataCount = ManageRegion::getCount($wheres);
		$dataList = self::arObjects2Array(ManageRegion::getList($wheres, $orders, $pageSize));
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}

	static function countyGetList($regionId = 0)
	{
		if($regionId){
		  $wheres['region_id']=intval($regionId);            //查询相对应的子类
		}else{
		  $wheres['']=array('region_id', '>', '0');  //查询所有子类
		}
		$pageSize='0';
		$orders = array('id' => 'DESC');
		$dataCount = ManageRegion::getCount($wheres);
		$dataList = self::arObjects2Array(ManageRegion::getList($wheres, $orders, $pageSize));
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}
}