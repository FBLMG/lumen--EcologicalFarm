<?php

namespace App\Services;

use App\Services\BaseService;
use App\Manages\ManageRegion;
use App\Manages\ManageFarm;

/**
 * 业务 地区
 * User: Administrator
 * Date: 2017/9/02
 * Time: 16:06
 */
class ServiceAdminRegion extends BaseService
{
	static $errorArray = array(
		'er1001' => '地区ID不能为空',
		'er1002' => '地区名称不能为空',
		'er1003' => '上级地区ID不能为空',
		'er1004' => '下级地区ID不能为空',


		'er2001' => '地区获取失败',
		'er2002' => '已存在相同地区名',
		'er2003' => '添加上级地区失败',
		'er2004' => '编辑上级地区失败',
		'er2005' => '删除上级地区失败',
		'er2006' => '已有农场绑定给地区',

		'er3001' => '添加下级地区失败',
		'er3002' => '获取下级地区失败',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 地区

	/**
	 * 获取地区
	 * @param int $id
	 * @return array
	 */
	static function regionGet($id = 0)
	{
		if(empty($id)){   //地区ID为空返回错误
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageRegion::get($id));   //查询不到地区返回错误
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		return self::returnSuccess($data);
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 上级地区



	/**
	 * 获取地区列表（根据上级地区名，时间查询）
	 * @param string $name
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @return array
	 */
	static function regionGetList($name = '', $startAt = 0, $endAt = 0,  $pageSize = 0)
	{
		$wheres = array();
		if ($name) {      //存在地区名称作为查询条件
			$wheres['name'] = array('LIKE', '%' . $name . '%');
		}
		if ($startAt) {   //存在开始时间作为查询条件
			$wheres[] = array('create_at', '>=', intval($startAt));
		} 
		if ($endAt) {     //存在结束时间作为查询条件
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		$wheres['region_id']='0';   //只查询父级的
		$orders = array('id' => 'DESC');
		$dataCount = ManageRegion::getCount($wheres);
		$dataList = self::arObjects2Array(ManageRegion::getList($wheres, $orders, $pageSize));
		foreach ($dataList as $key => $value) {
			$pageSizes=0;
			$whereSub=array();
			$whereSub['region_id']=$value['id'];
			$Sub=self::arObjects2Array(ManageRegion::getList($whereSub, $orders, $pageSizes));
			$dataList[$key]['subRegion']=$Sub;

		}
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}

	/**
	 * 添加上级地区
	 * @param string $name
	 * @return array
	 */
	static function parentRegionInsert($name = '')
	{
		if (empty($name)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
        $wheres=array();
        $wheres['name']=$name;      //是否相同地区名
        $wheres['region_id']='0';   //是否处于同一等级
        $existName=self::arObject2Array(ManageRegion::getWithWheres($wheres));
        if($existName){    //已存在以上条件返回错误
        	return self::returnError('er2002', self::$errorArray['er2002']);
        }
		$params = array(
			'name' => $name,
			'region_id' => '0',   //0代表该地区为父级
		);
		$result = ManageRegion::insert($params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2003', self::$errorArray['er2003']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 编辑上级地区
	 * @param int $id
	 * @param string $name
	 * @return array
	 */
	static function parentRegionUpdate($id = 0, $name = '')
	{
		if (empty($id)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
		$parentRegion=self::arObject2Array(ManageRegion::get($id)); //获取该数据
		if(empty($parentRegion)){   //不存在该数据返回错误
           return self::returnError('er3002', self::$errorArray['er3002']);
		}
		if (empty($name)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
        $wheres=array();
        $wheres['name']=$name;      //是否相同地区名
        $wheres['region_id']='0';   //是否处于同一等级
        $existName=self::arObject2Array(ManageRegion::getWithWheres($wheres));
        if($existName){    //存在已有地区名时判断是否是同一数据
        	if($existName['id']!=$id){
               return self::returnError('er2002', self::$errorArray['er2002']);
        	}
        }
		$params = array(
			'name' => $name,
		);
		$result = ManageRegion::update($id, $params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2004', self::$errorArray['er2004']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 移除上级地区（真实删除）
	 * @param int $id
	 * @return array
	 */
	static function parentRegionDelete($id = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageRegion::get($id));  //查询是否存在该数据
		if (empty($data)) {  //不存在该数据返回错误
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		$wheres=array();
		$wheres['region_id']=$id;
		$existFram=self::arObject2Array(ManageFarm::getWithWheres($wheres));  //查询是否有农场绑定给地区
		if($existFram){   //存在农场绑定该地区则返回错误
            return self::returnError('er2006', self::$errorArray['er2006']);
		}
		$where=array();
		$wheres['region_id']=$id;
		$result1 = ManageRegion::deleteWithWheres($wheres);   //删除相关子地区
		$result = ManageRegion::delete($id);   //删除上级地区
		if (self::resultEmpty($result)) {
			return self::returnError('er2005', self::$errorArray['er2005']);
		}
		return self::returnSuccess($result);
	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 下级地区



	/**
	 * 获取子地区列表（根据上级地区ID）
	 * @param int $regionId
	 * @param string $name
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @return array
	 */
	static function subRegionGet($id = 0,$name = '',$startAt = '',$endAt = '',$pageSize = 0)
	{
		$wheres = array();
		if (empty($id)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
		if ($name) {      //存在县级地区名称作为查询条件
			$wheres['name'] = array('LIKE', '%' . $name . '%');
		}
		if ($startAt) {   //存在开始时间作为查询条件
			$wheres[] = array('create_at', '>=', intval($startAt));
		} 
		if ($endAt) {     //存在结束时间作为查询条件
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		$wheres['region_id'] = intval($id);
		$orders = array('id' => 'DESC');
		$dataCount = ManageRegion::getCount($wheres);
		$dataList = self::arObjects2Array(ManageRegion::getList($wheres, $orders, $pageSize));
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}

	/**
	 * 添加下级地区
	 * @param int $regionId
	 * @param string $name
	 * @return array
	 */
	static function subRegionInsert($regionId = 0 ,$name = '')
	{
		if (empty($regionId)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
		if (empty($name)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
        $wheres=array();
        $wheres['name']=$name;      //是否相同地区名
        $wheres['region_id']=$regionId;   //是否处于同一父级下
        $existName=self::arObject2Array(ManageRegion::getWithWheres($wheres));
        if($existName){    //已存在以上条件返回错误
        	return self::returnError('er2002', self::$errorArray['er2002']);
        }
		$params = array(
			'name' => $name,
			'region_id' => $regionId,   //非代表该子地区
		);
		$result = ManageRegion::insert($params);
		if (self::resultEmpty($result)) {
			return self::returnError('er3001', self::$errorArray['er3001']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 编辑下级地区
	 * @param int $id
	 * @param string $name
	 * @return array
	 */
	static function subRegionUpdate($id = 0, $name = '')
	{
		if (empty($id)) {
			return self::returnError('er1004', self::$errorArray['er1004']);
		}
		if (empty($name)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		$subRegion=self::arObject2Array(ManageRegion::get($id)); //获取该数据
		if(empty($subRegion)){
           return self::returnError('er3002', self::$errorArray['er3002']);
		}
        $wheres=array();
        $wheres['name']=$name;      //是否相同地区名
        $wheres['region_id']=$subRegion['regionId'];   //是否处于同一等级
        $existName=self::arObject2Array(ManageRegion::getWithWheres($wheres));
        if($existName){    //存在已有地区名时判断是否是同一数据
        	if($existName['id']!=$id){
               return self::returnError('er2002', self::$errorArray['er2002']);
        	}
        }
		$params = array(
			'name' => $name,
		);
		$result = ManageRegion::update($id, $params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2004', self::$errorArray['er2004']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 移除下级地区（真实删除）
	 * @param int $id
	 * @return array
	 */
	static function subRegionDelete($id = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageRegion::get($id));  //查询是否存在该数据
		if (empty($data)) {  //不存在该数据返回错误
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		$wheres=array();
		$wheres['county_id']=$id;   //子地区
		$existFram=self::arObject2Array(ManageFarm::getWithWheres($wheres));  //查询是否有农场绑定给地区
		if($existFram){   //存在农场绑定该地区则返回错误
            return self::returnError('er2006', self::$errorArray['er2006']);
		}
		$where=array();
		$result = ManageRegion::delete($id);   //删除上级地区
		if (self::resultEmpty($result)) {
			return self::returnError('er2005', self::$errorArray['er2005']);
		}
		return self::returnSuccess($result);
	}
}