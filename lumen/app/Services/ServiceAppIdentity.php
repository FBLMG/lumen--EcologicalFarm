<?php

namespace App\Services;

use App\Manages\ManageUser;
use App\Manages\ManageFarm;
use App\Component\ComponentRandom;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * 业务 前台身份类
 * User: Administrator
 * Date: 2016/7/13
 * Time: 10:12
 */
class ServiceAppIdentity extends BaseService
{
	static $errorArray = array();

	/**
	 * 是否登录
	 * @param $userId
	 * @param $userToken
	 * @param int $userTestId
	 * @return bool|mixed
	 */
	static function isLogin($userId, $userToken, $userTestId = 0)
	{
		$user = self::__getUser($userId);
		if (empty($user)) {
			return false;
		}
		if($user['farmId']>0){
		  $farm=self::arObject2Array(ManageFarm::get($user['farmId']));   //查询农场	
		  $user['farmName']=$farm['title'];  
		}else{
		  $user['farmName']='';  
		}	
		if ($user['token'] === $userToken && $user['tokenOverAt'] > time()) {
			return $user;
		}
		if ($userTestId && $userTestId === intval($user['id'])) {
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': 当前用户为测试用户，ID（' . $userTestId . '）');
			return $user;
		}
		return false;
	}
   
    /**
     *前端用户处理微信登陆
     * @param $wxOpenId
     * @param $wxNickname
     * @param $wxAvatar
     * @param $wxSex
	 * @return array
     */
    static function login($wxOpenId ,$wxNickname = '',$wxAvatar = '',$wxSex = 3){
    	// 检查OpenId
        if (empty($wxOpenId)) {
			return false;
		}
		DB::beginTransaction();
        try {
		    //检查是否存在该用户
		    $where=array();
		    $where['wx_open_id']=$wxOpenId;
		    $user=self::arObject2Array(ManageUser::getWithWheres($where));
		    //不存在创建用户
		    if(empty($user)){
                $params = array(
			       'farm_id' => '0',
			       'wx_open_id' => $wxOpenId,
			       'wx_nickname' => $wxNickname,
			       'wx_avatar' => $wxAvatar,
			       'wx_sex' => $wxSex,
			       'power' => '2',
			       'level' => '3',
			       'status' => '1',
		        );
		        $userId=ManageUser::insert($params);  
		        if(empty($userId)){
                   return false;
		        }
		        $user=self::arObject2Array(ManageUser::get($userId));   //创建用户重新查询该用户信息
		    }  
		    //更新用户微信信息(昵称、头像、性别)
            if ($user && $wxNickname && $wxAvatar && $wxSex) {
                $param = array(
			      'wx_nickname' => $wxNickname,
			      'wx_avatar' => $wxAvatar,
			      'wx_sex' => $wxSex,
		        );
			    ManageUser::update($user['id'],$param);
		    }
            // 更新Token
			$userToken = self::createToken($user['id']);
			$userTokenOverTime = time() + $_ENV['params']['apiAppTokenOverTime'];
			$paramToken = array(
			  'token' => $userToken,
			  'token_over_at' => $userTokenOverTime,
			);
			$result = ManageUser::update($user['id'],$paramToken);
			if (empty($result)) {
				DB::rollBack();
				return false;
			}
			// 修复$user的Token信息
			$user['token'] = $userToken;
			$user['tokenOverAt'] = $userTokenOverTime;
			$user['tokenLastAt'] = time();
			//返回登陆用户信息
			DB::commit();
            return $user;
        } catch (\Exception $e) {
			DB::rollBack();
			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': ' . $e->getMessage());
			return false;
		}  
    }

    /**
	 * 创建Token
	 * @param $userId
	 * @return string
	 */
	static function createToken($userId)
	{
		return md5($userId . ComponentRandom::genRandomStr(32));
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//

	/**
	 * 获取用户（缓存）
	 * @param $userId
	 * @return mixed
	 */
	static function __getUser($userId)
	{
		return self::arObject2Array(ManageUser::get($userId));
	}
}