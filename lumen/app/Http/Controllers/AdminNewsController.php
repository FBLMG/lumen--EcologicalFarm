<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AdminBaseController;
use App\Services\ServiceAdminNews;

class AdminNewsController extends AdminBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 新闻

	/**
	 * 新闻管理
	 */

	/**
	 * 获取新闻详情
	 * http://localhost/admin/newsGet?id=1（新闻id）
	 */
	public function newsGet()
	{
		$this->echoReturn(ServiceAdminNews::newsGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取新闻列表
	 * http://localhost/admin/newsGetList?title=title（新闻标题）&startAt=0（开始时间）&endAt=0（结束时间）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function newsGetList()
	{
		$this->echoReturn(ServiceAdminNews::newsGetList(
			$this->request('title', ''),
			$this->request('startAt', 0),
			$this->request('endAt', 0),
			$this->request('pageSize', 0)
		));
	}

	/**
	 * 添加新闻
	 * http://localhost/admin/newsInsert?title=title（新闻标题）&images=images（封面）&content=content（新闻内容）&video=video.mp4（视频,暂时可不传）
	 */
	public function newsInsert()
	{
		$this->echoReturn(ServiceAdminNews::newsInsert(
			$this->request('title', ''),
			$this->request('images', ''),
			$this->request('content', ''),
			$this->request('video', '')
		));
	}

	/**
	 * 编辑新闻
	 * http://localhost/admin/newsUpdate?id=1（新闻id）&title=title（新闻标题）&images=images（封面）&content=content（新闻内容）&video=video.mp4（视频,暂时可不传）
	 */
	public function newsUpdate()
	{
		$this->echoReturn(ServiceAdminNews::newsUpdate(
			$this->request('id', 0),
			$this->request('title', ''),
			$this->request('images', ''),
			$this->request('content', ''),
			$this->request('video', '')
		));
	}

	/**
	 * 移除新闻
	 * http://localhost/admin/newsDelete?id=1（新闻id）
	 */
	public function newsDelete()
	{
		$this->echoReturn(ServiceAdminNews::newsDelete(
			$this->request('id', 0)
		));
	}


}
