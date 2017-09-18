<?php

namespace App\Services;

use App\Services\BaseService;
use App\Manages\ManageFarm;
use App\Manages\ManageRegion;
use App\Manages\ManageArchives;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * 业务 农场
 * User: Administrator
 * Date: 2017/9/02
 * Time: 16:06
 */
class ServiceAdminFarm extends BaseService
{
	static $errorArray = array(
		'er1001' => '农场Id不能为空',
		'er1002' => '农场名称不能为空',
		'er1003' => '农场内容不能为空',
		'er1004' => '农场上级地区不能为空',
        'er1005' => '农场下级地区不能为空',
        'er1006' => '农场封面图片不能为空',


		'er2001' => '农场获取失败',
		'er2002' => '不存在上级地区',
		'er2003' => '不存在下级地区',
		'er2004' => '已存在相同农场名称',
		'er2005' => '农场创建失败',
		'er2006' => '农场编辑失败',
		'er2007' => '该农场有绑定农场主，请先解除绑定再删除',
		'er2008' => '农场删除失败',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 农场

	/**
	 * 获取农场
	 * @param int $id
	 * @return array
	 */
	static function farmGet($id = 0)
	{
		if(empty($id)){   //农场ID为空返回错误
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageFarm::get($id));   //查询不到农场返回错误
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		$region=self::arObject2Array(ManageRegion::get($data['regionId']));  //查询上级地区
		$county=self::arObject2Array(ManageRegion::get($data['countyId']));  //查询下级地区
		$data['region']=$region['name'];  //上级地区
		$data['county']=$county['name'];  //下级地区
		return self::returnSuccess($data);
	}

	/**
	 * 获取农场列表（根据农场名称,地区,时间查询）
	 * @param string $name
	 * @param int $regionId
	 * @param int $countyId
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @return array
	 */
	static function farmGetList($name = '',$regionId = 0, $countyId = 0, $startAt = 0, $endAt = 0,  $pageSize = 0)
	{
		$wheres = array();
		if ($name) {          //存在农场名称作为查询条件
			$wheres['title'] = array('LIKE', '%' . $name . '%');
		}
		if ($regionId) {      //存在上级地区作为查询条件
			$wheres['region_id'] = intval($regionId);
		}
		if ($countyId) {      //存在下级地区作为查询条件
			$wheres['county_id'] = intval($countyId);
		}
		if ($startAt) {       //存在开始时间作为查询条件
			$wheres[] = array('create_at', '>=', intval($startAt));
		} 
		if ($endAt) {         //存在结束时间作为查询条件
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		$orders = array('id' => 'DESC');
		$dataCount = ManageFarm::getCount($wheres);
		$dataList = self::arObjects2Array(ManageFarm::getList($wheres, $orders, $pageSize));
		foreach ($dataList as $key => $value) {
			$region=self::arObject2Array(ManageRegion::get($value['regionId']));  //查询上级地区
			$county=self::arObject2Array(ManageRegion::get($value['countyId']));  //查询下级地区
			$dataList[$key]['region']=$region['name'];  //上级地区
			$dataList[$key]['county']=$county['name'];  //下级地区
			$content = strip_tags($value['content']);  //过滤掉所有的HTML标签
			$dataList[$key]['content']=$content;       //将过滤掉后的HTML标签重新赋回去
		}
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}

	/**
	 * 添加农场
	 * @param string $name
	 * @param string $images
	 * @param string $content
	 * @param string $video
	 * @param int $regionId
	 * @param int $countyId
	 * @return array
	 */
	static function farmInsert($name = '',$images = '', $content = '',  $video = '', $regionId = 0,$countyId = 0)
	{
		if (empty($name)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		if (empty($images)) {
			return self::returnError('er1006', self::$errorArray['er1006']);
		}
		if (empty($content)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
		if (empty($regionId)) {
			return self::returnError('er1004', self::$errorArray['er1004']);
		}
		if (empty($countyId)) {
			return self::returnError('er1005', self::$errorArray['er1005']);
		}
		DB::beginTransaction();
		try {
            //查询是否存在地区ID
            $existRegionId=self::arObject2Array(ManageRegion::get($regionId));  //查询上级地区
            $where=array();
            $where['id']=$countyId;
            $where['region_id']=$regionId;
            $existCountyId=self::arObject2Array(ManageRegion::getWithWheres($where));  //查询下级地区
            if(empty($existRegionId)){   //不存在上级地区返回错误
               DB::rollBack();
               return self::returnError('er2002', self::$errorArray['er2002']);
            }
            if(empty($existCountyId)){         //不存在下级地区返回错误
               DB::rollBack();
               return self::returnError('er2003', self::$errorArray['er2003']);
            }
            //查询同一地区是否存在相同的农场名称
            $wheres=array();
            $wheres['title']=$name;
            $wheres['region_id']=$regionId;
            $wheres['county_id']=$countyId;
            $exisName=self::arObject2Array(ManageFarm::getWithWheres($wheres));
            if($exisName){   //存在相同农场则返回错误
               DB::rollBack();
        	   return self::returnError('er2004', self::$errorArray['er2004']); 
            }
		    $params = array(
			   'user_id'=> '0',
			   'title' => $name,
			   'images' => $images,
			   'content' => $content,
			   'video' => $video,
			   'region_id'=> $regionId ,
			   'county_id'=> $countyId,
		    );
		    $result = ManageFarm::insert($params);
		    if (self::resultEmpty($result)) {
		      DB::rollBack();
			  return self::returnError('er2005', self::$errorArray['er2005']);
		    }
		    DB::commit();
		    return self::returnSuccess($result);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': ' . $e->getMessage());
			return self::returnError('er2005', self::$errorArray['er2005']);
		}
	}

	/**
	 * 编辑农场
	 * @param int $id
	 * @param string $name
	 * @param string $images
	 * @param string $content
	 * @param string $video
	 * @param int $regionId
	 * @param int $countyId
	 * @return array
	 */
	static function farmUpdate($id = 0, $name = '', $images = '',  $content = '',  $video = '', $regionId = 0,$countyId = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageFarm::get($id));  //查询是否要编辑的农场数据存在
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		if (empty($name)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		if (empty($images)) {
			return self::returnError('er1006', self::$errorArray['er1006']);
		}
		if (empty($content)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
		if (empty($regionId)) {
			return self::returnError('er1004', self::$errorArray['er1004']);
		}
		if (empty($countyId)) {
			return self::returnError('er1005', self::$errorArray['er1005']);
		}
		DB::beginTransaction();
		try {
		    //查询是否存在地区ID
            $existRegionId=self::arObject2Array(ManageRegion::get($regionId));  //查询上级地区
            $where['id']=$countyId;
            $where['region_id']=$regionId;
            $existCountyId=self::arObject2Array(ManageRegion::getWithWheres($where));  //查询下级地区
            if(empty($existRegionId)){   //不存在上级地区返回错误
               DB::rollBack();
               return self::returnError('er2002', self::$errorArray['er2002']);
            }
            if(empty($existCountyId)){         //不存在下级地区返回错误
               DB::rollBack();
               return self::returnError('er2003', self::$errorArray['er2003']);
            } 
            //查询同一地区是否存在相同的农场名称
            $wheres=array();
            $wheres['title']=$name;
            $wheres['region_id']=$regionId;
            $wheres['county_id']=$countyId;
            $exisName=self::arObject2Array(ManageFarm::getWithWheres($wheres));
            if($exisName){   //存在相同农场则返回错误
        	    if($exisName['id']!=$id){   //如果已存在的农场名不是当前修改的农场名则返回错误
        	       DB::rollBack();
                   return self::returnError('er2004', self::$errorArray['er2004']); 
        	    }
            }
		    $params = array(
			   'user_id'=> '0',
			   'title' => $name,
			   'images' => $images,
			   'content' => $content,
			   'video' => $video,
			   'region_id'=> $regionId ,
			   'county_id'=> $countyId,
		    );
		    $result = ManageFarm::update($id, $params);
		    if (self::resultEmpty($result)) {
		      DB::rollBack();
			  return self::returnError('er2006', self::$errorArray['er2006']);
		    }
		    DB::commit();
		    return self::returnSuccess($result);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': ' . $e->getMessage());
			return self::returnError('er2006', self::$errorArray['er2006']);
		}
	}

	/**
	 * 移除农场（真实删除）
	 * @param int $id
	 * @return array
	 */
	static function farmDelete($id = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageFarm::get($id));   //查询即将被删除的农场是否存在
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
        if($data['userId']>0){    //如果该农场已有绑定农场主则返回信息
            return self::returnError('er2007', self::$errorArray['er2007']);
        }
		$result = ManageFarm::delete($id);
		if (self::resultEmpty($result)) {
			return self::returnError('er2008', self::$errorArray['er2008']);
		}
		return self::returnSuccess($result);
	}
}