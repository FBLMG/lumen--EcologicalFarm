<?php

namespace App\Console;

use App\Manages\ManageActivityAward;
use App\Manages\ManageActivityUseRecord;
use App\Manages\ManageShopGoods;
use App\Manages\ManageUser;
use App\Services\BaseService;
use App\Services\ServiceAppShop;
use App\Services\ServiceCommonCzy;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		//
		$schedule->call(function () {


	
		})->everyMinute();
	}
}
