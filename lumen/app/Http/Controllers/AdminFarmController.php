<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminBaseController;
use App\Services\ServiceAdminFarm;

class AdminFarmController extends AdminBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 农场

	/**
	 * 农场管理
	 */

	/**
	 * 获取农场详情
	 * http://localhost/admin/farmGet?id=1（农场id）
	 */
	public function farmGet()
	{
		$this->echoReturn(ServiceAdminFarm::farmGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取农场列表
	 * http://localhost/admin/farmGetList?name=徐加庄（农场名称）&regionId=0（上级地区id）&countyId=0（下级地区id）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function farmGetList()
	{
		$this->echoReturn(ServiceAdminFarm::farmGetList(
			$this->request('name', ''),
			$this->request('regionId', 0),
			$this->request('countyId', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加农场
	 * http://localhost/admin/farmInsert?name=徐加庄（农场名称）&images=images（单张图片地址）&content=content（内容）&video=video.mp4（视频）&regionId=0（上级地区id）&countyId=0（下级地区id）
	 */
	public function farmInsert()
	{
		$this->echoReturn(ServiceAdminFarm::farmInsert(
			$this->request('name', ''),
			$this->request('images', ''),
			$this->request('content', ''),
			$this->request('video', ''),
			$this->request('regionId', 0),
			$this->request('countyId', 0)
		));
	}

	/**
	 * 编辑农场
	 * http://localhost/admin/farmUpdate?id=1（农场id）&name=徐加庄（农场名称）&images=images（单张图片地址）&content=content（内容）&video=video.mp4（视频）&regionId=0（上级地区id）&countyId=0（下级地区id）
	 */
	public function farmUpdate()
	{
		$this->echoReturn(ServiceAdminFarm::farmUpdate(
			$this->request('id', ''),
            $this->request('name', ''),
            $this->request('images', ''),
			$this->request('content', ''),
			$this->request('video', ''),
			$this->request('regionId', 0),
			$this->request('countyId', 0)
		));
	}

	/**
	 * 移除农场
	 * http://localhost/admin/farmDelete?id=1（农场id）
	 */
	public function farmDelete()
	{
		$this->echoReturn(ServiceAdminFarm::farmDelete(
			$this->request('id', 0)
		));
	}


}
