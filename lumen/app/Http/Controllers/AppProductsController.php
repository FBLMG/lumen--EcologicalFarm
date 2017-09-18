<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Services\ServiceAppProducts;
use App\Services\ServiceAppGrowthStatus;
use App\Services\ServiceAppVaccineStatus;
use App\Services\ServiceAppFeedStatus;
use App\Services\ServiceAppCirculationRecord;


class AppProductsController extends AppBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 产品溯源管理

	/**
	 * 产品溯源信息管理(农场主)
	 */

	/**
	 * 产品溯源信息
	 */

	/**
	 * 获取产品溯源详情
	 * http://localhost/app/productGet?id=1（产品溯源ID）
	 */
	public function productGet()
	{
		$this->echoReturn(ServiceAppProducts::productGet(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}

	/**
	 * 获取产品溯源列表
	 * http://localhost/app/productGetList?name=name（档案名称）&nextId=0（产品溯源ID）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function productGetList()
	{
		$this->echoReturn(ServiceAppProducts::productGetList(
			$this->userId,
			$this->user['farmId'],
			$this->request('name', ''),
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加产品溯源信息
	 * http://localhost/app/productInsert?fileName=07批散养鸡（档案名称）&productName=散养鸡（产品名称）&productCategory=1（产品类别 1：肉质类 2：果蔬类）&growingArea=山里（生长产地）&enterpriseInformation=徐加庄（企业信息）&registrationTime=0（登记时间）&shelfLife=9个月（保质期限）&feedingQuantity=0（饲养数量）&authenticationInformation=通过国家安全机构认证（认证信息）
	 */
	public function productInsert()
	{
		$this->echoReturn(ServiceAppProducts::productInsert(
			$this->userId,
			$this->user['farmId'],
			$this->request('fileName', ''),
			$this->request('productName', ''),
			$this->request('productCategory', 0),
			$this->request('growingArea', ''),
			$this->request('enterpriseInformation', ''),
			$this->request('registrationTime', 0),
			$this->request('shelfLife', ''),
			$this->request('feedingQuantity', 0),
			$this->request('authenticationInformation', '')
		));
	}


	/**
	 * 编辑产品溯源信息
	 * http://localhost/app/productUpdate?id=1（产品溯源ID）&fileName=07批散养鸡（档案名称）&productName=散养鸡（产品名称）&productCategory=1（产品类别 1：肉质类 2：果蔬类）&growingArea=山里（生长产地）&enterpriseInformation=徐加庄（企业信息）&registrationTime=0（登记时间）&shelfLife=9个月（保质期限）&feedingQuantity=0（饲养数量）&authenticationInformation=通过国家安全机构认证（认证信息）
	 */
	public function productUpdate()
	{
		$this->echoReturn(ServiceAppProducts::productUpdate(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0),
			$this->request('fileName', ''),
			$this->request('productName', ''),
			$this->request('productCategory', 0),
			$this->request('growingArea', ''),
			$this->request('enterpriseInformation', ''),
			$this->request('registrationTime', 0),
			$this->request('shelfLife', ''),
			$this->request('feedingQuantity', 0),
			$this->request('authenticationInformation', '')
		));
	}

	/**
	 * 移除产品溯源信息
	 * http://localhost/app/productDelete?id=1（产品溯源ID）
	 */
	public function productDelete()
	{
		$this->echoReturn(ServiceAppProducts::productDelete(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}



   	/**
	 * 产品溯源信息--生长情况
	 */

   	/**
	 * 获取生长情况详情
	 * http://localhost/app/growthStatusGet?id=1（生长情况ID）
	 */
	public function growthStatusGet()
	{
		$this->echoReturn(ServiceAppGrowthStatus::growthStatusGet(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}

	/**
	 * 获取生长情况列表
	 * http://localhost/app/growthStatusGetList?archivesId=1（档案ID[必传参数]）&nextId=0（疫苗情况ID）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function growthStatusGetList()
	{
		$this->echoReturn(ServiceAppGrowthStatus::growthStatusGetList(
            $this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加生长情况
	 * http://localhost/app/growthStatusInsert?archivesId=1（档案ID）&content=title（内容）&images=array（图片[数组格式]）&video=video.mp4（视频）
	 */
	public function growthStatusInsert()
	{
		$this->echoReturn(ServiceAppGrowthStatus::growthStatusInsert(
			$this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('content', ''),
			$this->request('images', array()),
			$this->request('video', '')
		));
	}

	/**
	 * 编辑生长情况
	 * http://localhost/app/growthStatusUpdate?id=1（生长情况ID）&content=title（内容）&images=array（图片[数组格式]）&video=video.mp4（视频）
	 */
	public function growthStatusUpdate()
	{
		$this->echoReturn(ServiceAppGrowthStatus::growthStatusUpdate(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0),
			$this->request('content', ''),
			$this->request('images', array()),
			$this->request('video', '')
		));
	}

	/**
	 * 移除生长情况
	 * http://localhost/app/growthStatusDelete?id=1（生长情况ID）
	 */
	public function growthStatusDelete()
	{
		$this->echoReturn(ServiceAppGrowthStatus::growthStatusDelete(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}

	/**
	 * 产品溯源信息--疫苗情况
	 */

   	/**
	 * 获取疫苗情况详情
	 * http://localhost/app/vaccineStatusGet?id=1（疫苗情况ID）
	 */
	public function vaccineStatusGet()
	{
		$this->echoReturn(ServiceAppVaccineStatus::vaccineStatusGet(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}

	/**
	 * 获取疫苗情况列表
	 * http://localhost/app/vaccineStatusGetList?archivesId=1（档案ID[必传参数]）&startAt=0（开始时间）&endAt=0（结束时间）&nextId=0（疫苗情况ID）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function vaccineStatusGetList()
	{
		    $this->echoReturn(ServiceAppVaccineStatus::vaccineStatusGetList(
            $this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加疫苗情况
	 * http://localhost/app/vaccineStatusInsert?archivesId=1（档案ID）&content=title（内容）
	 */
	public function vaccineStatusInsert()
	{
		$this->echoReturn(ServiceAppVaccineStatus::vaccineStatusInsert(
			$this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('content', '')
		));
	}

	/**
	 * 编辑疫苗情况
	 * http://localhost/app/vaccineStatusUpdate?id=1（疫苗情况ID）&content=title（内容）
	 */
	public function vaccineStatusUpdate()
	{
		$this->echoReturn(ServiceAppVaccineStatus::vaccineStatusUpdate(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0),
			$this->request('content', '')
		));
	}

	/**
	 * 移除疫苗情况
	 * http://localhost/app/vaccineStatusDelete?id=1（疫苗情况ID）
	 */
	public function vaccineStatusDelete()
	{
		$this->echoReturn(ServiceAppVaccineStatus::vaccineStatusDelete(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}



	/**
	 * 产品溯源信息--饲料情况
	 */

   	/**
	 * 获取饲料情况详情
	 * http://localhost/app/feedStatusGet?id=1（饲料情况ID）
	 */
	public function feedStatusGet()
	{
		$this->echoReturn(ServiceAppFeedStatus::feedStatusGet(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}

	/**
	 * 获取饲料情况列表
	 * http://localhost/app/feedStatusGetList?archivesId=1（档案ID[必传参数]）&nextId=0（饲料情况ID）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function feedStatusGetList()
	{
		$this->echoReturn(ServiceAppFeedStatus::feedStatusGetList(
			$this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加饲料情况
	 * http://localhost/app/feedStatusInsert?archivesId=1（档案ID）&content=title（内容）
	 */
	public function feedStatusInsert()
	{
		$this->echoReturn(ServiceAppFeedStatus::feedStatusInsert(
			$this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('content', '')
		));
	}

	/**
	 * 编辑饲料情况
	 * http://localhost/app/feedStatusUpdate?id=1（饲料情况ID）&content=title（内容）
	 */
	public function feedStatusUpdate()
	{
		$this->echoReturn(ServiceAppFeedStatus::feedStatusUpdate(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0),
			$this->request('content', '')
		));
	}

	/**
	 * 移除饲料情况
	 * http://localhost/app/feedStatusDelete?id=1（饲料情况ID）
	 */
	public function feedStatusDelete()
	{
		$this->echoReturn(ServiceAppFeedStatus::feedStatusDelete(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}


	/**
	 * 产品溯源信息--流通记录情况
	 */

   	/**
	 * 获取流通记录情况详情
	 * http://localhost/app/circulationRecordGet?id=1（流通记录情况ID）
	 */
	public function circulationRecordGet()
	{
		$this->echoReturn(ServiceAppCirculationRecord::circulationRecordGet(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}

	/**
	 * 获取流通记录列表
	 * http://localhost/app/circulationRecordGetList?archivesId=1（档案ID[必传参数]）&startAt=0（开始时间）&endAt=0（结束时间）&nextId=0（流通记录ID）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function circulationRecordGetList()
	{
		$this->echoReturn(ServiceAppCirculationRecord::circulationRecordGetList(
			$this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加流通记录情况
	 * http://localhost/app/circulationRecordInsert?archivesId=1（档案ID）&content=title（内容）
	 */
	public function circulationRecordInsert()
	{
		$this->echoReturn(ServiceAppCirculationRecord::circulationRecordInsert(
			$this->userId,
			$this->user['farmId'],
			$this->request('archivesId', 0),
			$this->request('content', '')
		));
	}

	/**
	 * 编辑流通记录情况
	 * http://localhost/app/circulationRecordUpdate?id=1（流通记录ID）&content=title（内容）
	 */
	public function circulationRecordUpdate()
	{
		$this->echoReturn(ServiceAppCirculationRecord::circulationRecordUpdate(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0),
			$this->request('content', '')
		));
	}

	/**
	 * 移除流通记录情况
	 * http://localhost/app/circulationRecordDelete?id=1（流通记录ID）
	 */
	public function circulationRecordDelete()
	{
		$this->echoReturn(ServiceAppCirculationRecord::circulationRecordDelete(
			$this->userId,
			$this->user['farmId'],
			$this->request('id', 0)
		));
	}
}
