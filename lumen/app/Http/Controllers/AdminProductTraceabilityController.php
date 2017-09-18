<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminBaseController;
use App\Services\ServiceAdminProductTraceability;

class AdminProductTraceabilityController extends AdminBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 产品溯源

	/**
	 * 产品溯源管理
	 */

	/**
	 * 获取产品溯源详情
	 * http://localhost/admin/productTraceabilityGet?id=1（产品溯源id）
	 */
	public function productTraceabilityGet()
	{
		$this->echoReturn(ServiceAdminProductTraceability::productTraceabilityGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取产品溯源列表
	 * http://localhost/admin/productTraceabilityGetList?regionId=0（上级地区）&countyId=0（下级地区）&name=name（档案名称）&userId=0（农场主id）&farmId=0（农场id）&productName=name（产品名称）&productCategory=0（产品类别 1：肉质类 2：果蔬类）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function productTraceabilityGetList()
	{
		$this->echoReturn(ServiceAdminProductTraceability::productTraceabilityGetList(
			$this->request('regionId', 0),
			$this->request('countyId', 0),
			$this->request('name', ''),
			$this->request('userId', 0),
			$this->request('farmId', 0),
			$this->request('productName', ''),
			$this->request('productCategory', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0)
		));
	}

}
