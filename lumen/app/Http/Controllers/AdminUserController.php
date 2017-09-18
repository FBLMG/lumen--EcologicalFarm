<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminBaseController;
use App\Services\ServiceAdminUser;
use Illuminate\Support\Facades\Cache;

class AdminUserController extends AdminBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 用户

	/**
	 * 管理者管理
	 */

	/**
	 * 获取管理者详情
	 * http://localhost/admin/adminGet?id=1（管理者id）
	 */
	public function adminGet()
	{
		$this->echoReturn(ServiceAdminUser::adminGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取管理者列表
	 * http://localhost/admin/adminGetList?username=username（管理者名称）&status=0（状态 1：正常 2：禁用）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function adminGetList()
	{
		$this->echoReturn(ServiceAdminUser::adminGetList(
			$this->request('username', ''),
			$this->request('status', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加管理者
	 * http://localhost/admin/adminInsert?username=username（管理者名称）&password=123456（管理者密码）
	 */
	public function adminInsert()
	{
		$this->echoReturn(ServiceAdminUser::adminInsert(
			$this->request('username', ''),
			$this->request('password', '')
		));
	}

	/**
	 * 编辑管理者
	 * http://localhost/admin/adminUpdate?id=1（管理者id）&username=username（管理者名称）&password=123456（管理者密码）
	 */
	public function adminUpdate()
	{
		$this->echoReturn(ServiceAdminUser::adminUpdate(
			$this->request('id', 0),
			$this->request('username', ''),
			$this->request('password', '')
		));
	}

	/**
	 * 移除管理者
	 * http://localhost/admin/adminDelete?id=1（管理者id）
	 */
	public function adminDelete()
	{
		$this->echoReturn(ServiceAdminUser::adminDelete(
			$this->request('id', 0)
		));
	}

	/**
	 * 启用管理者
	 * http://localhost/admin/adminStart?id=1（管理者id）
	 */
	public function adminStart()
	{
		$this->echoReturn(ServiceAdminUser::adminStart(
			$this->request('id', 0)
		));
	}

	/**
	 * 禁用管理者
	 * http://localhost/admin/adminStop?id=1（管理者id）
	 */
	public function adminStop()
	{
		$this->echoReturn(ServiceAdminUser::adminStop(
			$this->request('id', 0)
		));
	}

	/**
	 * 前台用户管理
	 */


	/**
	 * 获取前端用户详情
	 * http://localhost/admin/userGet?id=1（前端用户id）
	 */
	public function userGet()
	{
		$this->echoReturn(ServiceAdminUser::userGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取前端用户列表
	 * http://localhost/admin/userGetList?name=name（前端用户名称）&power=1（权限 1：农场主 2：会员）&level=0（级别 1：皇冠会员 2：钻石会员 3：星级会员）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function userGetList()
	{
		$this->echoReturn(ServiceAdminUser::userGetList(
			$this->request('name', ''),
			$this->request('power', 0),
			$this->request('level', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 修改前端用户级别
	 * http://localhost/admin/userUpdateLevel?id=1（前端用户id）&level=0（级别 1：皇冠会员 2：钻石会员 3：星级会员）
	 */
	public function userUpdateLevel()
	{
		$this->echoReturn(ServiceAdminUser::userUpdateLevel(
			$this->request('id', 0),
			$this->request('level', 0)
		));
	}

	/**
	 * 修改前端用户权限(会员转换为农场主)
	 * http://localhost/admin/userUpdatepower?id=1（前端用户id）&farmId=0（农场ID,成为会员时，可不传）
	 */
	public function userUpdatepower()
	{
		$this->echoReturn(ServiceAdminUser::userUpdatepower(
			$this->request('id', 0),
			$this->request('farmId', 0)
		));
	}
    

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 缓存

	/**
	 * 缓存
	 */

	/**
	 * 清空所有缓存
	 * http://localhost/admin/cleanCache
	 */
	public function cleanCache()
	{
		Cache::flush();
		$this->echoSuccess(1);
	}


}
