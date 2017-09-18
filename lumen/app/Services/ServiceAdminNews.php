<?php

namespace App\Services;

use App\Services\BaseService;
use App\Manages\ManageNews;

/**
 * 业务 新闻
 * User: Administrator
 * Date: 2017/9/02
 * Time: 16:06
 */
class ServiceAdminNews extends BaseService
{
	static $errorArray = array(
		'er1001' => '新闻Id不能为空',
		'er1002' => '新闻标题不能为空',
		'er1003' => '新闻内容不能为空',
		'er1004' => '新闻封面不能为空',


		'er2001' => '新闻获取失败',
		'er2002' => '新闻添加失败',
		'er2003' => '新闻编辑失败',
		'er2004' => '新闻删除失败',
	);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 新闻

	/**
	 * 获取新闻
	 * @param int $id
	 * @return array
	 */
	static function newsGet($id = 0)
	{
		if(empty($id)){   //新闻ID为空返回错误
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageNews::get($id));   //查询不到新闻返回错误
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		return self::returnSuccess($data);
	}

	/**
	 * 获取新闻列表（根据标题，时间查询）
	 * @param string $title
	 * @param int $startAt
	 * @param int $endAt
	 * @param int $pageSize
	 * @return array
	 */
	static function newsGetList($title = '', $startAt = 0, $endAt = 0,  $pageSize = 0)
	{
		$wheres = array();
		if ($title) {      //存在标题作为查询条件
			$wheres['title'] = array('LIKE', '%' . $title . '%');
		}
		if ($startAt) {   //存在开始时间作为查询条件
			$wheres[] = array('create_at', '>=', intval($startAt));
		} 
		if ($endAt) {     //存在结束时间作为查询条件
			$wheres[] = array('create_at', '<=', intval($endAt));
		}
		$orders = array('id' => 'DESC');
		$dataCount = ManageNews::getCount($wheres);
		$dataList = self::arObjects2Array(ManageNews::getList($wheres, $orders, $pageSize));
		foreach ($dataList as $key => $value) {
			$content = strip_tags($value['content']);  //过滤掉所有的HTML标签
			$dataList[$key]['content']=$content;       //将过滤掉后的HTML标签重新赋回去
		}
		return self::returnSuccess(array('dataList' => $dataList, 'dataCount' => $dataCount));
	}

	/**
	 * 添加新闻
	 * @param string $title
	 * @param string $images
	 * @param string $content
	 * @param string $video
	 * @return array
	 */
	static function newsInsert($title = '',$images = '', $content = '',  $video = '')
	{
		if (empty($title)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		if (empty($content)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
		if(empty($images)){
            return self::returnError('er1004', self::$errorArray['er1004']);
		}
		$params = array(
			'title' => $title,
			'images' => $images,
			'content' => $content,
			'video' => $video,
		);
		$result = ManageNews::insert($params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2002', self::$errorArray['er2002']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 编辑新闻
	 * @param int $id
	 * @param string $title
	 * @param string $images
	 * @param string $content
	 * @param string $video
	 * @return array
	 */
	static function newsUpdate($id = 0, $title = '',$images = '', $content = '',  $video = '')
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageNews::get($id));
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		if (empty($title)) {
			return self::returnError('er1002', self::$errorArray['er1002']);
		}
		if(empty($images)){
            return self::returnError('er1004', self::$errorArray['er1004']);
		}
		if (empty($content)) {
			return self::returnError('er1003', self::$errorArray['er1003']);
		}
		$params = array(
			'title' => $title,
			'images' => $images,
			'content' => $content,
			'video' => $video,
		);
		$result = ManageNews::update($id, $params);
		if (self::resultEmpty($result)) {
			return self::returnError('er2003', self::$errorArray['er2003']);
		}
		return self::returnSuccess($result);
	}

	/**
	 * 移除新闻（真实删除）
	 * @param int $id
	 * @return array
	 */
	static function newsDelete($id = 0)
	{
		if (empty($id)) {
			return self::returnError('er1001', self::$errorArray['er1001']);
		}
		$data = self::arObject2Array(ManageNews::get($id));
		if (empty($data)) {
			return self::returnError('er2001', self::$errorArray['er2001']);
		}
		$result = ManageNews::delete($id);
		if (self::resultEmpty($result)) {
			return self::returnError('er2004', self::$errorArray['er2004']);
		}
		return self::returnSuccess($result);
	}
}