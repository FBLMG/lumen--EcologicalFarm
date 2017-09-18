<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Services\ServiceAppProductTraceability;

class AppProductTraceabilityController extends AppBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 产品溯源查看

	/**
	 * 产品溯源(农场主跟会员)
	 */

	/**
	 * 获取产品溯源详情
	 * http://localhost/app/ProductTraceabilityGet?id=1（产品溯源ID）
	 */
	public function ProductTraceabilityGet()
	{
		$this->echoReturn(ServiceAppProductTraceability::ProductTraceabilityGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取产品溯源列表
	 * http://localhost/app/ProductTraceabilityGetList?name=name（档案名称）&regionId=0（地区ID）&countyId=0（县镇ID）&nextId=0（产品溯源ID）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function ProductTraceabilityGetList()
	{
		$this->echoReturn(ServiceAppProductTraceability::ProductTraceabilityGetList(
			$this->request('name', ''),
			$this->request('regionId', 0),
			$this->request('countyId', 0),
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

}
