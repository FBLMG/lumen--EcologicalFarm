<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminBaseController;
use App\Services\ServiceAdminRegion;

class AdminRegionController extends AdminBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 地区

	/**
	 * 地区管理
	 */


	/**
	 * 获取地区名称
	 * http://localhost/admin/regionGet?id=1（地区id）
	 */
	public function regionGet()
	{
		$this->echoReturn(ServiceAdminRegion::regionGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 上级地区
	 */


	/**
	 * 获取地区列表(包含上级地区与他的下一级)
	 * http://localhost/admin/regionGetList?name=河源（地区名称）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function regionGetList()
	{
		$this->echoReturn(ServiceAdminRegion::regionGetList(
			$this->request('name', ''),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0)
		));
	}
    
	/**
	 * 添加上级地区
	 * http://localhost/admin/parentRegionInsert?name=河源（地区名称）
	 */
	public function parentRegionInsert()
	{
		$this->echoReturn(ServiceAdminRegion::parentRegionInsert(
			$this->request('name', '')
		));
	}

	/**
	 * 编辑上级地区
	 * http://localhost/admin/parentRegionUpdate?id=1（地区id）&name=河源（地区名称）
	 */
	public function parentRegionUpdate()
	{
		$this->echoReturn(ServiceAdminRegion::parentRegionUpdate(
			$this->request('id', 0),
			$this->request('name', '')
		));
	}

	/**
	 * 移除上级地区
	 * http://localhost/admin/parentRegionDelete?id=1（地区id）
	 */
	public function parentRegionDelete()
	{
		$this->echoReturn(ServiceAdminRegion::parentRegionDelete(
			$this->request('id', 0)
		));
	}
 

	/**
	 * 下级地区
	 */

     /**
	 * 获取子地区列表(只包含下级地区)
	 * http://localhost/admin/subRegionGet?id=1（上级地区id）&name=龙川县（县镇名称）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function subRegionGet()
	{
		$this->echoReturn(ServiceAdminRegion::subRegionGet(
			$this->request('id', 0),
			$this->request('name', ''),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加下级地区
	 * http://localhost/admin/subRegionInsert?regionId=1（上级地区ID）&name=龙川县（地区名称）
	 */
	public function subRegionInsert()
	{
		$this->echoReturn(ServiceAdminRegion::subRegionInsert(
			$this->request('regionId', 0),
			$this->request('name', '')
		));
	}

	/**
	 * 编辑下级地区
	 * http://localhost/admin/subRegionUpdate?id=1（地区id）&name=龙川县（地区名称）
	 */
	public function subRegionUpdate()
	{
		$this->echoReturn(ServiceAdminRegion::subRegionUpdate(
			$this->request('id', 0),
			$this->request('name', '')
		));
	}

	/**
	 * 移除下级地区
	 * http://localhost/admin/subRegionDelete?id=1（地区id）
	 */
	public function subRegionDelete()
	{
		$this->echoReturn(ServiceAdminRegion::subRegionDelete(
			$this->request('id', 0)
		));
	}

}
