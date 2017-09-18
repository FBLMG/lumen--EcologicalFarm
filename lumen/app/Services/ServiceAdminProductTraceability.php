<?php

namespace App\Services;

use App\Services\BaseService;
use App\Manages\ManageArchives;
use App\Manages\ManageGrowthStatus;
use App\Manages\ManageFeedStatus;
use App\Manages\ManageVaccineStatus;
use App\Manages\ManageCirculationRecord;
use App\Manages\ManageFarm;
use App\Manages\ManageUser;
use App\Manages\ManageRegion;

/**
 * 业务 产品溯源
 * User: Administrator
 * Date: 2017/9/02
 * Time: 16:06
 */
class ServiceAdminProductTraceability extends BaseService
{
	static $errorArray = array(
		'er1001' => '产品溯源Id不能为空',


		'er2001' => '产品溯源获取失败',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 产品溯源

	/**
	 * 获取产品溯源
	 * @param int $id
	 * @return array
	 */
	static function productTraceabilityGet($id = 0)
	{
		if(empty($id)){   //农场ID为空返回错误
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageArchives::get($id));   
		if (empty($data)) {   //查询不到产品溯源返回错误
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		//查询农场信息
		$dataFarm = self::arObject2Array(ManageFarm::get($data['farmId']));  
		$data['farmName']=$dataFarm['title'];    //农场名称
		//查询农场主
		$dataUser = self::arObject2Array(ManageUser::get($data['userId']));  
		$data['userName']=$dataUser['wxNickname'];    //农场主姓名
		//查询地区信息
		$regin=self::arObject2Array(ManageRegion::get($data['regionId']));  //上级地区
        $county=self::arObject2Array(ManageRegion::get($data['countyId']));  //下级地区
	    $data['reginName']=$regin['name'];
		$data['countyName']=$county['name'];   
        /*查询档案相关信息*/
        $growthStatus=self::__getGrowthStatus($id);                  //获取生长情况
        $feedStatus=self::__getFeedStatus($id);                      //查询饲养情况
        $vaccineStatus=self::__getVaccineStatus($id);                //查询疫苗情况 
        $circulationRecord=self::__getCirculationRecord($id);        //查询流通记录
        /*查询赋值*/
        $data['growthStatus']=$growthStatus;               //生长情况
        $data['feedStatus']=$feedStatus;                   //饲养情况
        $data['vaccineStatus']=$vaccineStatus;             //疫苗情况
        $data['circulationRecord']=$circulationRecord;     //流通记录

		return self::returnSuccess($data);
	}

	/**
	 * 获取产品溯源列表（根据档案名称,农场主id,农场id,产品名称,产品类别,时间查询）
	 * @param int $regionId
	 * @param int $countyId
	 * @param string $name
	 * @param int $userId
	 * @param int $farmId
	 * @param string $productName
	 * @param int $productCategory
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @return array
	 */
	static function productTraceabilityGetList($regionId = 0 ,$countyId = 0,$name = '',$userId = 0, $farmId = 0,$productName = '', $productCategory = 0, $startAt = 0, $endAt = 0,  $pageSize = 0)
	{
		$wheres = array();
		if ($regionId) {         //存在上级地区ID作为查询条件
			$wheres['region_id'] = intval($regionId);
		}
		if ($countyId) {         //存在下级地区ID作为查询条件
			$wheres['county_id'] = intval($countyId);
		}
		if ($name) {             //存在档案名称作为查询条件
			$wheres['file_name'] = array('LIKE', '%' . $name . '%');
		}
		if ($userId) {           //存在农场主ID作为查询条件
			$wheres['user_id'] = intval($userId);
		}
		if ($farmId) {           //存在农场ID作为查询条件
			$wheres['farm_id'] = intval($farmId);
		}
		if ($productName) {      //存在产品名称作为查询条件
			$wheres['product_name'] = array('LIKE', '%' . $productName . '%');
		}
		if ($productCategory) {  //存在产品类别作为查询条件
			$wheres['product_category'] = intval($productCategory);
		}
		if ($startAt) {          //存在开始时间作为查询条件
			$wheres[] = array('create_at', '>=', intval($startAt));
		} 
		if ($endAt) {            //存在结束时间作为查询条件
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		$orders = array('id' => 'DESC');
		$dataCount = ManageArchives::getCount($wheres);
		$dataList = self::arObjects2Array(ManageArchives::getList($wheres, $orders, $pageSize));
		foreach ($dataList as $key => $value) {
			//获取农场名称
			$farm=self::arObject2Array(ManageFarm::get($value['farmId']));
			$dataList[$key]['farmName']=$farm['title'];
			//获取农场主
			$user=self::arObject2Array(ManageUser::get($value['userId']));
			$dataList[$key]['userName']=$user['wxNickname'];
			//获取地区
            $regin=self::arObject2Array(ManageRegion::get($value['regionId']));  //上级地区
            $county=self::arObject2Array(ManageRegion::get($value['countyId']));  //下级地区
			$dataList[$key]['reginName']=$regin['name'];
			$dataList[$key]['countyName']=$county['name'];     
		}
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}
 

 	//////////////////////////////////////产品详情其他信息获取///////////////////////////////////////////////
	/////////////////////////

	 /**
	 * 获取生长情况
	 * @param $id
	 * @return array
	 */  
    static function __getGrowthStatus($id = 0){
    	$orders = array('id' => 'DESC');
        $pageSize=0; 
        $whereGrowthStatus=array();
        $whereGrowthStatus['archives_id']=$id;
        $growthStatus=self::arObjects2Array(ManageGrowthStatus::getList($whereGrowthStatus,$orders,$pageSize));  
        foreach ($growthStatus as $key => $value) {    //对序列化的图片进行反序列化
         	$images=unserialize($value['images']);   
			$growthStatus[$key]['images']=$images;
        } 
        return $growthStatus;
    }

    /**
	 * 获取疫苗情况
	 * @param $id
	 * @return array
	 */
    static function __getVaccineStatus($id = 0){
        $orders = array('id' => 'DESC');
        $pageSize=0; 
        $whereVaccineStatus=array();
        $whereVaccineStatus['archives_id']=$id;
        $vaccineStatus=self::arObjects2Array(ManageVaccineStatus::getList($whereVaccineStatus,$orders,$pageSize));
        return $vaccineStatus;
    }

     /**
	 * 获取饲养情况
	 * @param $id
	 * @return array
	 */
    static function __getFeedStatus($id = 0){
    	$orders = array('id' => 'DESC');
        $pageSize=0; 
        $whereFeedStatus=array();
        $whereFeedStatus['archives_id']=$id;
        $feedStatus=self::arObjects2Array(ManageFeedStatus::getList($whereFeedStatus,$orders,$pageSize)); 
        return $feedStatus;
    }

     /**
	 * 获取记录流通情况
	 * @param $id
	 * @return array
	 */
    static function __getCirculationRecord($id = 0){
        $orders = array('id' => 'DESC');
        $pageSize=0; 
        $whereCirculationRecord=array();
        $whereCirculationRecord['archives_id']=$id;
        $circulationRecord=self::arObjects2Array(ManageCirculationRecord::getList($whereCirculationRecord,$orders,$pageSize)); 
        return $circulationRecord;
    }


}