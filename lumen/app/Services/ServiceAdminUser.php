<?php

namespace App\Services;

use App\Services\BaseService;
use App\Services\ServiceAdminIdentity;
use App\Manages\ManageAdmin;
use App\Manages\ManageUser;
use App\Manages\ManageFarm;
use App\Manages\ManageArchives;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


/**
 * 业务 管理员
 * User: Administrator
 * Date: 2017/9/02
 * Time: 16:06
 */
class ServiceAdminUser extends BaseService
{
	static $errorArray = array(
		'er1001' => '管理员Id不能为空',
		'er1002' => '管理员用户名不能为空',
		'er1003' => '管理员密码不能为空',

		'er3001' => '用户ID不能为空',
        'er3002' => '农场ID不能为空',
        'er3003' => '用户权限不能为空',

		'er2001' => '管理员获取失败',
		'er2002' => '已存在相同用户名',
		'er2003' => '管理员添加失败',
		'er2004' => '管理员编辑失败',
		'er2005' => '管理员删除失败',
		'er2006' => '管理员已处于启用状态',
		'er2007' => '管理员已处于禁用状态',
		'er2008' => '管理员状态改变失败',

		'er4001' => '用户获取失败',
		'er4002' => '用户级别编辑失败失败',
		'er4003' => '农场获取失败',
		'er4004' => '该农场已有农场主',
		'er4005' => '该用户已为农场会员',
		'er4007' => '该用户为农场主,没有会员级别的修改',

	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 管理员

	/**
	 * 获取管理员
	 * @param int $id
	 * @return array
	 */
	static function adminGet($id = 0)
	{
		if(empty($id)){   //管理员ID为空返回错误
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageAdmin::get($id));   //查询不到管理员返回错误
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		return self::returnSuccess($data);
	}

	/**
	 * 获取管理者列表（根据管理员名称,地区,时间查询）
	 * @param string $username
	 * @param int $status
	 * @param int $endAt
	 * @param int $pageSize
	 * @return array
	 */
	static function adminGetList($username = '', $status = 0, $startAt = 0, $endAt = 0,  $pageSize = 0)
	{
		$wheres = array();
		if ($username) {      //存在管理员名称作为查询条件
			$wheres['username'] = array('LIKE', '%' . $username . '%');
		}
		if ($status) {      //存在状态作为查询条件
			$wheres['status'] = intval($status);
		}
		if ($startAt) {   //存在开始时间作为查询条件
			$wheres[] = array('create_at', '>=', intval($startAt));
		} 
		if ($endAt) {     //存在结束时间作为查询条件
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		$orders = array('id' => 'DESC');
		$dataCount = ManageAdmin::getCount($wheres);
		$dataList = self::arObjects2Array(ManageAdmin::getList($wheres, $orders, $pageSize));
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}

	/**
	 * 添加管理者
	 * @param string $username
	 * @param string $password
	 * @return array
	 */
	static function adminInsert($username = '', $password = '')
	{
		if (empty($username)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		if (empty($password)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
        $where=array();
        $where['username']=$username;
        $existName=self::arObject2Array(ManageAdmin::getWithWheres($where));  //查询是否存在相同账号
        if($existName){
           return self::returnError('er2002', self::$errorArray['er2002']);
        }
        $password=ServiceAdminIdentity::encryptPassword($password);  //密码加密
        $params = array(
			'username'=> $username,
			'password' => $password,
			'power' => '1',      //默认管理员
			'status' => '1',     //默认状态为正常
		);
		$result = ManageAdmin::insert($params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2003', self::$errorArray['er2003']);
		}
        return self::returnSuccess($result);
	}

	/**
	 * 编辑管理者
	 * @param int $id
	 * @param string $username
	 * @param string $password
	 * @return array
	 */
	static function adminUpdate($id = 0, $username = '', $password = '')
	{
		$params=array();
		if(empty($id)){
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		if (empty($username)){
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		if($password){
			$password=ServiceAdminIdentity::encryptPassword($password);  //密码加密
			$params['password']=$password;
		}
        $data=self::arObject2Array(ManageAdmin::get($id));  //查询是否存在管理员
        if(empty($data)){
           return self::returnError('er2001', self::$errorArray['er2001']);
        }
        $whereName=array();
        $whereName['username']=$username;
        $isName=self::arObject2Array(ManageAdmin::getWithWheres($whereName));  //查询是否已存在相同用户名
        if($isName){
           if($isName['id']!=$id){    //存在相同用户名返回错误信息
              return self::returnError('er2002', self::$errorArray['er2002']);
           }
        }    
        $params['username']=$username;
        $result = ManageAdmin::update($id, $params);
        if (self::resultEmpty($result)) {
			return self::returnError('er2004', self::$errorArray['er2004']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 移除管理员（真实删除）
	 * @param int $id
	 * @return array
	 */
	static function adminDelete($id = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageAdmin::get($id));   //查询即将被删除的管理员是否存在
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		$result = ManageAdmin::delete($id);
		if (self::resultEmpty($result)) {
			return self::returnError('er2005', self::$errorArray['er2005']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 启用管理员
	 * @param int $id
	 * @return array
	 */
	static function adminStart($id = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageAdmin::get($id));   //查询管理员是否存在
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		if($data['status']==1){   //如果管理员处于启用状态返回错误
            return self::returnError('er2006', self::$errorArray['er2006']);
		}
		//修改管理员状态
		$params=array(
           'status'=> '1',	
        );
        $result = ManageAdmin::update($id, $params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2008', self::$errorArray['er2008']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 禁用管理员
	 * @param int $id
	 * @return array
	 */
	static function adminStop($id = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageAdmin::get($id));   //查询管理员是否存在
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		if($data['status']==2){  //如果管理员处于禁用状态返回错误信息
            return self::returnError('er2007', self::$errorArray['er2007']);
		}
		//修改管理员状态
		$params=array(
           'status'=> '2',	
        );
        $result = ManageAdmin::update($id, $params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2008', self::$errorArray['er2008']);
		}
		return self::returnSuccess($result);
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 前端用户

    /**
	 * 获取前端用户
	 * @param int $id
	 * @return array
	 */
	static function userGet($id = 0)
	{
		if(empty($id)){   //用户ID为空返回错误
			return self::returnError('er3001', self::$errorArray['er3001']);
		}
		$data = self::arObject2Array(ManageUser::get($id));   //查询不到用户返回错误
		if (empty($data)) {
			return self::returnError('er4001', self::$errorArray['er4001']);
		}
		$farmName=self::arObject2Array(ManageFarm::get($data['farmId']));    //查询该用户所在农场信息
		$data['farmName']=$farmName['title'];  //农场名称
		return self::returnSuccess($data);
	}

	/**
	 * 获取前端用户列表（根据前端用户名称,权限,级别,状态,时间查询）
	 * @param string $name
	 * @param int $power
	 * @param int $level
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @return array
	 */
	static function userGetList($name = '',$power = 0, $level=0, $startAt = 0, $endAt = 0,  $pageSize = 0)
	{
		$wheres = array();
		if ($name) {      //存在用户名称作为查询条件
			$wheres['wx_nickname'] = array('LIKE', '%' . $name . '%');
		}
		if ($power) {       //存在权限作为查询条件
			$wheres['power'] = intval($power);
		}
		if ($level) {       //存在级别作为查询条件
			$wheres['level'] = intval($level);
		}
		if ($startAt) {    //存在开始时间作为查询条件
			$wheres[] = array('create_at', '>=', intval($startAt));
		} 
		if ($endAt) {      //存在结束时间作为查询条件
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		$orders = array('id' => 'DESC');
		$dataCount = ManageUser::getCount($wheres);
		$dataList = self::arObjects2Array(ManageUser::getList($wheres, $orders, $pageSize));
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}

	/**
	 * 修改前端用户级别
	 * @param int $id
	 * @param int $level（级别 1：皇冠会员 2：钻石会员 3：星级会员）	 
	 * @return array
	 */
	static function userUpdateLevel($id = 0,$level = 0)
	{
		if (empty($id)) {
			return self::returnError('er3001', self::$errorArray['er3001']);
		}
		$data = self::arObject2Array(ManageUser::get($id));   //查询不到用户返回错误
		if (empty($data)) {
			return self::returnError('er4001', self::$errorArray['er4001']);
		}
		if($data['power']=='1'){  //若该用户为农场主，则无法修改级别
            return self::returnError('er4007', self::$errorArray['er4007']);
		}
		$params=array(
           'level'=> $level,	
        );
        $result = ManageUser::update($id, $params);
		if (self::resultEmpty($result)) {
			return self::returnError('er4002', self::$errorArray['er4002']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 修改前端用户权限(会员转变成农场主)
	 * @param int $id
	 * @param int $farmId（农场ID）
	 * @return array
	 */
	static function userUpdatepower($id = 0,$farmId = 0)
	{
		if (empty($id)) {
			return self::returnError('er3001', self::$errorArray['er3001']);
		}
		$data = self::arObject2Array(ManageUser::get($id));   //查询不到用户返回错误
		if (empty($data)) {
			return self::returnError('er4001', self::$errorArray['er4001']);
		}
	    DB::beginTransaction();
        try {
        	  if($data['power']=='1'){   //如果用户为农场主则返回信息
                 return self::returnError('er4005', self::$errorArray['er4005']);
        	  }
        	  $judgeFarm=self::__judgeFarm($farmId);  //私有方法用于判断输入农场是否可以
        	  if($judgeFarm!='tr1001'){
        	  	 DB::rollBack(); 
                 return self::returnError($judgeFarm, self::$errorArray[$judgeFarm]);
        	  }
        	  //修改绑定的农场
              $param=array(
                'user_id'=> $id,	
              );
              $where=array();
              $where['id']=$farmId;
              $result1=ManageFarm::updateWithWheres($where, $param);
		      if (self::resultEmpty($result1)) {
		        DB::rollBack();
			    return self::returnError('er4002', self::$errorArray['er4002']);
		      }
		      //修改用户表的农场ID
		      $params=array(
                'farm_id'=> $farmId,	
                'power'  => '1',
              );
              $result = ManageUser::update($id, $params);    //修改
		      if (self::resultEmpty($result)) {    
		        DB::rollBack(); 
			    return self::returnError('er4002', self::$errorArray['er4002']);
		      }
		      DB::commit();
		    return self::returnSuccess($result);
		} catch (\Exception $e) {
			DB::rollBack();
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': ' . $e->getMessage());
			return self::returnError('er4002', self::$errorArray['er4002']);
		}  
	}

    
    /**
	 * 查询用户农场输入是否正常
	 * @param int $farmId
	 * @return array
	 */
	static function __judgeFarm($farmId = 0)
	{
        if(empty($farmId)){         //农场ID为空时,返回错误
            return 'er3002';
        }
        $farm=self::arObject2Array(ManageFarm::get($farmId));   //查询是否存在该农场
        if(empty($farm)){           //不存在该农场返回错误    
            return 'er4003';
        }
        if($farm['userId']>0){      //该农场已有农场主返回错误
            return 'er4004';
        } 
		return 'tr1001';
	}


}