<?php

namespace App\Services;
use App\Manages\ManageArchives;
use App\Manages\ManageRegion;
use App\Manages\ManageGrowthStatus;
use App\Manages\ManageFeedStatus;
use App\Manages\ManageVaccineStatus;
use App\Manages\ManageCirculationRecord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 业务 前端产品溯源查看类
 * User: Administrator
 * Date: 2017/9/05
 * Time: 10:06
 */
class ServiceAppProductTraceability extends BaseService
{
	static $errorArray = array(
		'1001' => '产品溯源ID不能为空',

		'2001' => '产品溯源获取失败',
	);

	/**
	 * 获取产品溯源
	 * @return array
	 */
	static function ProductTraceabilityGet($id=0)
	{
		if(empty($id)){   //产品溯源ID为空返回错误
			return self::returnError('1001', self::$errorArray['1001']);
		}
		$data = self::arObject2Array(ManageArchives::get($id));    //查询档案基本详情
		$data['createAt']=date("Y-m-d", $data['createAt']);        //时间戳转换为日期
		/*查询档案其他信息*/
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
	 * 获取档案列表
	 * @param $name
	 * @param $regionId
	 * @param $countyId
	 * @param $nextId
	 * @param $pageSize
	 * @return array
	 */
	static function ProductTraceabilityGetList($name,$regionId,$countyId,$nextId, $pageSize)
	{
		// 获取缓存
		$cacheKey = self::getCacheKey(__CLASS__ . '.' . __FUNCTION__, $name . '_' . $regionId . '_' . $countyId . '_' . $nextId);
		$cacheResult = Cache::get($cacheKey);
		if ($cacheResult) {
			return self::returnSuccess($cacheResult);
		}
		//
		$dataList = self::arObjects2Array(ManageArchives::getListWithProductTraceability($name,$regionId,$countyId,$nextId, $pageSize));
		foreach ($dataList as $key => $value) {
			$region=self::arObject2Array(ManageRegion::get($value['regionId']));  //查询上级地区
			$county=self::arObject2Array(ManageRegion::get($value['countyId']));  //查询下级地区
			$dataList[$key]['region']=$region['name'];  //上级地区
			$dataList[$key]['county']=$county['name'];  //下级地区
			$createAt=date("Y-m-d", $value['createAt']);  //时间戳转换为日期
		    $dataList[$key]['createAt']=$createAt;        //重新赋值
		}
		// 设置缓存
		Cache::put($cacheKey, $dataList, self::getCacheTimeout(__CLASS__ . '.' . __FUNCTION__));
		//
		return self::returnSuccess($dataList);
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
			$createAt=date("Y-m-d", $value['createAt']);  //时间戳转换为日期
		    $growthStatus[$key]['createAt']=$createAt;          //重新赋值
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
        foreach ($vaccineStatus as $key => $value) {
        	$createAt=date("Y-m-d", $value['createAt']);  //时间戳转换为日期
		    $vaccineStatus[$key]['createAt']=$createAt;          //重新赋值
        }
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
        foreach ($feedStatus as $key => $value) {
        	$createAt=date("Y-m-d", $value['createAt']);  //时间戳转换为日期
		    $feedStatus[$key]['createAt']=$createAt;          //重新赋值
        }
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
        foreach ($circulationRecord as $key => $value) {
        	$createAt=date("Y-m-d", $value['createAt']);  //时间戳转换为日期
		    $circulationRecord[$key]['createAt']=$createAt;          //重新赋值
        }
        return $circulationRecord;
    }
}