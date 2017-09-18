<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Services\ServiceAppNews;

class AppNewsController extends AppBaseController
{

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 新闻

	/**
	 * 新闻
	 */

	/**
	 * 获取新闻详情
	 * http://localhost/app/newsGet?id=1（新闻ID）
	 */
	public function newsGet()
	{
		$this->echoReturn(ServiceAppNews::newsGet(
			$this->request('id', 0)
		));
	}

	/**
	 * 获取新闻列表
	 * http://localhost/app/newsGetList?nextId=0（新闻id）&pageSize=20（每页显示数量，0表示不使用分页）
	 */
	public function newsGetList()
	{
		$this->echoReturn(ServiceAppNews::newsGetList(
			$this->request('nextId', 0),
			$this->request('pageSize', 0)
		));
	}

}
