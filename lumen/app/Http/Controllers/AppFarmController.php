<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Services\ServiceAppFarm;

class AppFarmController extends AppBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 生态农场

	/**
	 * 生态农场
	 */

	/**
	 * 获取生态农场详情
	 * http://localhost/app/farmGet?id=1（农场ID）
	 */
	public function farmGet()
	{
		$this->echoReturn(ServiceAppFarm::farmGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取生态农场列表
	 * http://localhost/app/farmGetList?regionId=0（地区ID）&countyId=0（县镇ID）&nextId=0（农场id）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function farmGetList()
	{
		$this->echoReturn(ServiceAppFarm::farmGetList(
			$this->request('regionId', 0),
			$this->request('countyId', 0),
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 获取上级地区
	 * http://localhost/app/regionGetList
	 */
	public function regionGetList()
	{
		$this->echoReturn(ServiceAppFarm::regionGetList());
	}

	/**
	 * 获取下级地区
	 * http://localhost/app/countyGetList?regionId=1（上级地区ID）
	 */
	public function countyGetList()
	{
		$this->echoReturn(ServiceAppFarm::countyGetList(
			$this->request('regionId', 0)
		));
	}

}
