<?php

namespace App\Services;
use App\Manages\ManageFeedStatus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\ServiceAppProducts;

/**
 * 业务 前端产品溯源--饲养情况
 * User: Administrator
 * Date: 2017/9/05
 * Time: 10:06
 */
class ServiceAppFeedStatus extends BaseService
{
	static $errorArray = array(
		'1001' => '农场主ID不能为空',
		'1002' => '农场ID不能为空',
		'1003' => '饲养情况ID不能为空',
		'1004' => '产品溯源ID不能为空',
		'1005' => '饲养情况不能为空',

		'2001' => '饲养情况添加失败',
		'2002' => '饲养情况获取失败',
		'2003' => '饲养情况编辑失败',
		'2004' => '饲养情况删除失败',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 饲养情况管理


	/**
	 * 获取饲养情况
	 * @param int $userId
	 * @param int $farmId
	 * @param int $id
	 * @return array
	 */
	static function feedStatusGet($userId = 0 , $farmId = 0 , $id = 0)
	{
        if(empty($userId)){
           return self::returnError('1001', self::$errorArray['1001']); 
    	} 
    	if(empty($farmId)){
           return self::returnError('1002', self::$errorArray['1002']);
    	}
    	if(empty($id)){
           return self::returnError('1003', self::$errorArray['1003']);
    	}
    	$where=array();
        $where['user_id']=$userId;
        $where['farm_id']=$farmId;
        $where['id']=$id;
        $data= self::arObject2Array(ManageFeedStatus::getWithWheres($where));  
        //获取档案名称
        $archivesName=ServiceAppProducts::getArchivesName($data['archivesId']);  
		$data['archivesName']=$archivesName;
        return self::returnSuccess($data);
    }

    /**
	 * 饲养情况列表
	 * @param $userId
	 * @param $farmId
	 * @param $archivesId
	 * @param $startAt
	 * @param $endAt
	 * @param $nextId
	 * @param $pageSize
	 * @return array
	 */
	static function feedStatusGetList($userId = 0, $farmId = 0, $archivesId = 0, $startAt =0, $endAt = 0, $nextId = 0 ,$pageSize = 0)
	{
		if(empty($userId)){
            return self::returnError('1001', self::$errorArray['1001']);
		}
		if(empty($farmId)){
            return self::returnError('1002', self::$errorArray['1002']);
		}
		if(empty($archivesId)){
            return self::returnError('1004', self::$errorArray['1004']);
		}
		$dataList = self::arObjects2Array(ManageFeedStatus::getFeedStatusGetList($userId, $farmId, $archivesId, $startAt, $endAt, $nextId, $pageSize));
		foreach ($dataList as $key => $value) {
			//日期格式化
			$dataList[$key]['createAt']=date("Y-m-d", $value['createAt']);   //时间戳转换为日期
		}
		//获取档案名称
		$archivesName=ServiceAppProducts::getArchivesName($archivesId);  
		$archivesName=$archivesName;
		return self::returnSuccess(array('dataList' =>$dataList ,'archivesName' => $archivesName));
	}

    /**
	 * 添加饲养情况
	 * @param int $userId
	 * @param int $farmId
	 * @param int $archivesId
	 * @param string $content
	 * @return array
	 */
	static function feedStatusInsert($userId = 0, $farmId = 0, $archivesId = 0, $content = '')
	{
		if(empty($userId)){
            return self::returnError('1001', self::$errorArray['1001']);
		}
		if(empty($farmId)){
            return self::returnError('1002', self::$errorArray['1002']);
		}
		if(empty($archivesId)){
            return self::returnError('1004', self::$errorArray['1004']);
		}
		if(empty($content)){
            return self::returnError('1005', self::$errorArray['1005']);
		}
		$params = array(
			'user_id' => intval($userId),
			'farm_id' => intval($farmId),
			'archives_id' => intval($archivesId),
			'content' => $content,
		);
		$result = ManageFeedStatus::insert($params);
		if (self::resultEmpty($result)) {
			return self::returnError('2001', self::$errorArray['2001']);
		}
		return self::returnSuccess($result);
	}


    /**
	 * 编辑饲养情况
	 * @param int $userId
	 * @param int $farmId
	 * @param int $id
	 * @param string $content
	 * @return array
	 */
	static function feedStatusUpdate($userId = 0, $farmId = 0, $id = 0, $content = '')
	{
		if(empty($userId)){
            return self::returnError('1001', self::$errorArray['1001']);
		}
		if(empty($farmId)){
            return self::returnError('1002', self::$errorArray['1002']);
		}
		if(empty($id)){
            return self::returnError('1003', self::$errorArray['1003']);
		}
		if(empty($content)){
            return self::returnError('1005', self::$errorArray['1005']);
		}
		//查询是否存在编辑的数据
		$where['user_id']=$userId;
        $where['farm_id']=$farmId;
        $where['id']=$id;
        $data= self::arObject2Array(ManageFeedStatus::getWithWheres($where)); 
        if(empty($data)){
           return self::returnError('2002', self::$errorArray['2002']);
        }
        //修改饲养情况
		$params = array(
			'content' => $content,
		);
		$result = ManageFeedStatus::update($id,$params);
		if (self::resultEmpty($result)) {
			return self::returnError('2003', self::$errorArray['2003']);
		}
		return self::returnSuccess($result);
	}


    /**
	 * 移除饲养情况（真实删除）
	 * @param int $userId
	 * @param int $farmId
	 * @param int $id
	 * @return array
	 */
	static function feedStatusDelete($userId = 0, $farmId = 0, $id = 0)
	{
		if(empty($userId)){
            return self::returnError('1001', self::$errorArray['1001']);
		}
		if(empty($farmId)){
            return self::returnError('1002', self::$errorArray['1002']);
		}
		if(empty($id)){
            return self::returnError('1003', self::$errorArray['1003']);
		}
		//查询是否存在删除的数据
		$where['user_id']=$userId;
        $where['farm_id']=$farmId;
        $where['id']=$id;
        $data= self::arObject2Array(ManageFeedStatus::getWithWheres($where)); 
        if(empty($data)){
           return self::returnError('2002', self::$errorArray['2002']);
        }
        //删除饲养情况
		$result = ManageFeedStatus::delete($id);
		if (self::resultEmpty($result)) {
			return self::returnError('2004', self::$errorArray['2004']);
		}
		return self::returnSuccess($result);
	}

}