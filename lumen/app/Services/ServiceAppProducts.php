<?php

namespace App\Services;
use App\Manages\ManageFarm;
use App\Manages\ManageArchives;
use App\Manages\ManageGrowthStatus;
use App\Manages\ManageFeedStatus;
use App\Manages\ManageVaccineStatus;
use App\Manages\ManageCirculationRecord;
use App\Services\ServiceAppProductTraceability;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 业务 前端农场主产品溯源管理类
 * User: Administrator
 * Date: 2017/9/05
 * Time: 10:06
 */
class ServiceAppProducts extends BaseService
{
	static $errorArray = array(
		'1001' => '产品溯源ID不能为空',
		'1002' => '农场主ID不能为空',
		'1003' => '农场ID不能为空',
		'1004' => '档案名称不能为空',
		'1005' => '产品名称不能为空',
		'1006' => '产品类别不能为空',
		'1007' => '生长产地不能为空',
		'1008' => '企业信息不能为空',
		'1009' => '登记时间不能为空',
		'1010' => '保质期限不能为空',
		'1011' => '饲养数量不能为空',
		'1012' => '认证信息不能为空',
		'1013' => '农场地区有误',

		'2001' => '产品溯源获取失败',
		'2002' => '产品溯源添加失败',
		'2003' => '产品溯源添加失败',
		'2004' => '产品溯源删除失败',
		'2005' => '产品溯源获取失败',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 产品溯源管理

	/**
	 * 获取产品溯源
	 * @return array
	 */
	static function productGet($userId = 0 , $farmId = 0 , $id = 0)
	{
		if(empty($userId)){
            return self::returnError('1002', self::$errorArray['1002']);
		}
		if(empty($farmId)){
            return self::returnError('1003', self::$errorArray['1003']);
		}
		if(empty($id)){   //产品溯源ID为空返回错误
			return self::returnError('1001', self::$errorArray['1001']);
		}
		$where=array();
		$where['farm_id']=$farmId;   //农场ID
		$where['user_id']=$userId;   //农场主ID
		$where['id']=$id;            //档案ID
		$data = self::arObject2Array(ManageArchives::getWithWheres($where));    //查询档案基本详情
		if(empty($data)){  //没有数据返回错误
            return self::returnError('2005',self::$errorArray['2005']);
		}
		/*查询档案其他信息*/
        $growthStatus=ServiceAppProductTraceability::__getGrowthStatus($id);                  //获取生长情况
        $feedStatus=ServiceAppProductTraceability::__getFeedStatus($id);                      //查询饲养情况
        $vaccineStatus=ServiceAppProductTraceability::__getVaccineStatus($id);                //查询疫苗情况 
        $circulationRecord=ServiceAppProductTraceability::__getCirculationRecord($id);        //查询流通记录;    
        /*查询赋值*/
        $data['growthStatus']=$growthStatus;               //生长情况
        $data['feedStatus']=$feedStatus;                   //饲养情况
        $data['vaccineStatus']=$vaccineStatus;             //疫苗情况
        $data['circulationRecord']=$circulationRecord;     //流通记录
		return self::returnSuccess($data);
	}

	/**
	 * 产品溯源列表
	 * @param $name
	 * @param $regionId
	 * @param $countyId
	 * @param $nextId
	 * @param $pageSize
	 * @return array
	 */
	static function productGetList($userId = 0 , $farmId = 0 , $name = 0 , $nextId = 0 ,$pageSize = 0 )
	{
		if(empty($userId)){
            return self::returnError('1002', self::$errorArray['1002']);
		}
		if(empty($farmId)){
            return self::returnError('1003', self::$errorArray['1003']);
		}
		$dataList = self::arObjects2Array(ManageArchives::getListWithProduct($userId, $farmId, $name, $nextId, $pageSize));
		return self::returnSuccess($dataList);
	}
    
    /**
	 * 添加产品溯源
	 * @param $userId
	 * @param $farmId
	 * @param $fileName
	 * @param $productName
	 * @param $productCategory
	 * @param $growingArea
	 * @param $enterpriseInformation
	 * @param $registrationTime
	 * @param $shelfLife
	 * @param $feedingQuantity
	 * @param $authenticationInformation
	 * @return array
	 */

    static function productInsert($userId = 0,$farmId = 0, $fileName = '' , $productName = '' , $productCategory = 0 , $growingArea = '' , $enterpriseInformation = '' , $registrationTime = 0 , $shelfLife = '' , $feedingQuantity = 0 , $authenticationInformation = ''){
    	if(empty($userId)){
           return self::returnError('1002', self::$errorArray['1002']); 
    	} 
    	if(empty($farmId)){
           return self::returnError('1003', self::$errorArray['1003']);
    	}
    	if(empty($fileName)){
           return self::returnError('1004', self::$errorArray['1004']);
    	} 
    	if(empty($productName)){
           return self::returnError('1005', self::$errorArray['1005']); 
    	} 
    	if(empty($productCategory)){
           return self::returnError('1006', self::$errorArray['1006']);
    	}
    	if(empty($growingArea)){
           return self::returnError('1007', self::$errorArray['1007']);
    	} 
    	if(empty($enterpriseInformation)){
           return self::returnError('1008', self::$errorArray['1008']); 
    	} 
    	if(empty($registrationTime)){
           return self::returnError('1009', self::$errorArray['1009']);
    	}
    	if(empty($shelfLife)){
           return self::returnError('1010', self::$errorArray['1010']);
    	} 
    	if(empty($feedingQuantity)){
           return self::returnError('1011', self::$errorArray['1011']);
    	} 
    	if(empty($authenticationInformation)){
           return self::returnError('1012', self::$errorArray['1012']);
    	}
    	$region=self::getRegionId($userId,$farmId);  //查询农场地区
    	if(empty($region)){
           return self::returnError('1013', self::$errorArray['1013']); 
    	}
    	$regionId=$region['regionId'];  //上级地区
        $countyId=$region['countyId'];  //下级地区
        $params = array(
			'user_id'=> intval($userId),                                //农场主ID
			'farm_id' => intval($farmId),                               //农场ID
			'region_id' => intval($regionId),                           //上级地区ID
			'county_id' => intval($countyId),                           //下级地区ID
			'file_name'=> $fileName ,                                   //档案名称
			'product_name'=> $productName,                              //产品名称
			'product_category' => intval($productCategory),             //产品分类
			'growing_area' => $growingArea,                             //生长产地
			'enterprise_information'=> $enterpriseInformation,          //企业信息
			'registration_time'=> $registrationTime,                    //登记时间
			'shelf_life' => $shelfLife,                                 //保质期限
			'feeding_quantity'=> $feedingQuantity ,                     //饲养数量
			'authentication_information'=> $authenticationInformation,  //认证信息
		);
		$result = ManageArchives::insert($params);
		if (self::resultEmpty($result)) {
			return self::returnError('2002', self::$errorArray['2002']);
		}
		return self::returnSuccess($result);

    }


    /**
	 * 编辑产品溯源
	 * @param $userId
	 * @param $farmId
	 * @param $id
	 * @param $fileName
	 * @param $productName
	 * @param $productCategory
	 * @param $growingArea
	 * @param $enterpriseInformation
	 * @param $registrationTime
	 * @param $shelfLife
	 * @param $feedingQuantity
	 * @param $authenticationInformation
	 * @return array
	 */

    static function productUpdate($userId = 0 ,$farmId = 0 ,$id = 0 ,$fileName = '' , $productName = '' , $productCategory = 0 , $growingArea = '' , $enterpriseInformation = '' , $registrationTime = 0 , $shelfLife = '' , $feedingQuantity = 0 , $authenticationInformation = ''){
    	if(empty($userId)){
           return self::returnError('1002', self::$errorArray['1002']); 
    	} 
    	if(empty($farmId)){
           return self::returnError('1003', self::$errorArray['1003']);
    	}
    	if(empty($id)){
           return self::returnError('1001', self::$errorArray['1001']);
    	}
    	if(empty($fileName)){
           return self::returnError('1004', self::$errorArray['1004']);
    	} 
    	if(empty($productName)){
           return self::returnError('1005', self::$errorArray['1005']); 
    	} 
    	if(empty($productCategory)){
           return self::returnError('1006', self::$errorArray['1006']);
    	}
    	if(empty($growingArea)){
           return self::returnError('1007', self::$errorArray['1007']);
    	} 
    	if(empty($enterpriseInformation)){
           return self::returnError('1008', self::$errorArray['1008']); 
    	} 
    	if(empty($registrationTime)){
           return self::returnError('1009', self::$errorArray['1009']);
    	}
    	if(empty($shelfLife)){
           return self::returnError('1010', self::$errorArray['1010']);
    	} 
    	if(empty($feedingQuantity)){
           return self::returnError('1011', self::$errorArray['1011']);
    	} 
    	if(empty($authenticationInformation)){
           return self::returnError('1012', self::$errorArray['1012']);
    	}
    	$region=self::getRegionId($userId,$farmId);  //查询农场地区
    	if(empty($region)){
           return self::returnError('1013', self::$errorArray['1013']); 
    	}
    	DB::beginTransaction();
		try {
    	     //判断是否存在要修改的档案
    	    $regionId=$region['regionId'];  //上级地区
            $countyId=$region['countyId'];  //下级地区
            $where=array();
            $where['id']=$id;
            $where['user_id']=$userId;
            $where['farm_id']=$farmId;
            $where['region_id']=$regionId;
            $where['county_id']=$countyId;
            $data = self::arObject2Array(ManageArchives::getWithWheres($where));    //查询档案基本详情
            if(empty($data)){
               DB::rollBack();
               return self::returnError('2001', self::$errorArray['2001']); 
            }
            //修改档案
            $params = array(
			   'file_name'=> $fileName ,                                   //档案名称
			   'product_name'=> $productName,                              //产品名称
			   'product_category' => intval($productCategory),             //产品分类
			   'growing_area' => $growingArea,                             //生长产地
			   'enterprise_information'=> $enterpriseInformation,          //企业信息
			   'registration_time'=> $registrationTime,                    //登记时间
			   'shelf_life' => $shelfLife,                                 //保质期限
			   'feeding_quantity'=> $feedingQuantity ,                     //饲养数量
			   'authentication_information'=> $authenticationInformation,  //认证信息
		    );
		    $result = ManageArchives::update($id,$params);
		    if (self::resultEmpty($result)) {
		    	DB::rollBack();
			    return self::returnError('2003', self::$errorArray['2003']);
		    }
		    DB::commit();
		    return self::returnSuccess($result);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': ' . $e->getMessage());
			return self::returnError('2003', self::$errorArray['2003']);
		}

    }

    /**
	 * 移除产品溯源（真实删除）
	 * @param int $userId
	 * @param int $farmId
	 * @param int $id
	 * @return array
	 */
	static function productDelete($userId = 0,$farmId = 0,$id = 0)
	{
    	if(empty($userId)){
           return self::returnError('1002', self::$errorArray['1002']); 
    	} 
    	if(empty($farmId)){
           return self::returnError('1003', self::$errorArray['1003']);
    	}
    	if(empty($id)){
           return self::returnError('1001', self::$errorArray['1001']);
    	}
    	DB::beginTransaction();
		try {
    	    //查询是否存在该档案
    	    $wheres=array();
            $wheres['user_id']=$userId;
            $wheres['farm_id']=$farmId;
            $wheres['id']=$id;
            $result1= self::arObject2Array(ManageArchives::getWithWheres($wheres));   //查询是否存在该档案
            if (empty($result1)) {
            	DB::rollBack();
			    return self::returnError('2001', self::$errorArray['2001']);
		    }
    	    //删除档案其他信息
    	    $deleteGrowthStatus=self::deleteGrowthStatus($userId,$farmId,$id);            //删除生长情况
    	    $deleteFeedStatus=self::deleteFeedStatus($userId,$farmId,$id);                //删除饲养情况
    	    $deleteVaccineStatus=self::deleteVaccineStatus($userId,$farmId,$id);          //删除疫苗情况
    	    $deletecirculationRecord=self::deletecirculationRecord($userId,$farmId,$id);  //删除流通记录情况
    	    ////
            $where=array();
            $where['user_id']=$userId;
            $where['farm_id']=$farmId;
            $where['id']=$id;
            $result= ManageArchives::deleteWithWheres($where);   //删除档案里有关该农场的数据
            if (self::resultEmpty($result)) {
            	DB::rollBack();
			    return self::returnError('2004', self::$errorArray['2004']);
		    }
			DB::commit();
		    return self::returnSuccess($result);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': ' . $e->getMessage());
			return self::returnError('2004', self::$errorArray['2004']);
		}
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 私有类
    /**
	 * 获取农场地区
	 * @param $userId
	 * @param $farmId
	 * @return int
	 */
	static function getRegionId($userId,$farmId)
	{
        $where=array();
        $where['id']=$farmId;
        $where['user_id']=$userId;
        $farm=self::arObject2Array(ManageFarm::getWithWheres($where)); 
		//
		return $farm;
	}

	/**
	 * 删除生长情况
	 * @param $userId
	 * @param $farmId
	 * @return int
	 */
	static function deleteGrowthStatus($userId,$farmId,$id)
	{
        $where=array();
        $where['user_id']=$userId;
        $where['farm_id']=$farmId;
        $where['archives_id']=$id;
        $growthStatus=self::arObject2Array(ManageGrowthStatus::deleteWithWheres($where)); 
		//
		return $growthStatus;
	}

	/**
	 * 删除饲养情况
	 * @param $userId
	 * @param $farmId
	 * @param $id
	 * @return int
	 */
	static function deleteFeedStatus($userId,$farmId,$id)
	{
        $where=array();
        $where['user_id']=$userId;
        $where['farm_id']=$farmId;
        $where['archives_id']=$id;
        $feedStatus=self::arObject2Array(ManageFeedStatus::deleteWithWheres($where)); 
		//
		return $feedStatus;
	}

	/**
	 * 删除疫苗情况
	 * @param $userId
	 * @param $farmId
	 * @param $id
	 * @return int
	 */
	static function deleteVaccineStatus($userId,$farmId,$id)
	{
        $where=array();
        $where['user_id']=$userId;
        $where['farm_id']=$farmId;
        $where['archives_id']=$id;
        $vaccineStatus=self::arObject2Array(ManageVaccineStatus::deleteWithWheres($where)); 
		//
		return $vaccineStatus;
	}

	/**
	 * 删除流通记录情况
	 * @param $userId
	 * @param $farmId
	 * @param $id
	 * @return int
	 */
	static function deletecirculationRecord($userId,$farmId,$id)
	{
        $where=array();
        $where['user_id']=$userId;
        $where['farm_id']=$farmId;
        $where['archives_id']=$id;
        $circulationRecord=self::arObject2Array(ManageCirculationRecord::deleteWithWheres($where)); 
		//
		return $circulationRecord;
	}


	/////////////////////////////////////////////////其他信息共用方法//////////////////////////////////////////////

	/**
	 * 获取档案名称
	 * @param $archivesId
	 * @return int
	 */
	static function getArchivesName($archivesId = 0)
	{
        $archivesName=self::arObject2Array(ManageArchives::get($archivesId)); 
		return $archivesName['fileName'];
	}

}