<?php

namespace App\Http\Controllers;

use App\Jobs\ExampleJob;
use Illuminate\Support\Facades\Cache;

class ExampleController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * 测试使用
	 * http://localhost/example/test
	 */
	public function test()
	{
		// 计算测试使用的用户信息
		$userId = 2114586328;
		$requestTime = time();
		$czyResId = $_ENV['params']['czyResId'];
		$signNew = strtoupper(md5($czyResId . md5('request_time=' . $requestTime . '&user_id=' . $userId) . $czyResId));
		echo '?user_id=' . $userId . '&request_time=' . $requestTime . '&sign=' . $signNew;
		exit;
	}
}
