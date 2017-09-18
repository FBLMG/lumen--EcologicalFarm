<?php

namespace App\Jobs;

use App\Manages\ManageActivityAward;
use App\Manages\ManageActivityUseRecord;
use App\Manages\ManageUser;
use App\Services\BaseService;
use App\Services\ServiceCommonCzy;
use Illuminate\Support\Facades\Log;

class SendAwardJob extends Job
{
	private $activityUseRecordId = 0;
	private $awardType = 0;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($activityUseRecordId, $awardType)
	{
		//
		$this->activityUseRecordId = $activityUseRecordId;
		$this->awardType = $awardType;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
//		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/start-----------------------------------------------------------------');
//		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType);
//		// 获取使用记录
//		$activityUseRecord = BaseService::arObject2Array(ManageActivityUseRecord::get($this->activityUseRecordId));
//		if (empty($activityUseRecord)) {
//			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType . '/end/使用记录不存在-----------------------------------------------------------------');
//			return;
//		}
//		if ($activityUseRecord['statusExchange'] == 3) {
//			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType . '/end/奖品已兑换-----------------------------------------------------------------');
//			return;
//		}
//		// 获取奖品信息
//		$award = BaseService::arObject2Array(ManageActivityAward::get($activityUseRecord['awardId']));
//		if (empty($award)) {
//			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType . '/end/奖品不存在-----------------------------------------------------------------');
//			return;
//		}
//		// 获取用户信息
//		$userId = intval($activityUseRecord['userId']);
//		$user = BaseService::arObject2Array(ManageUser::get($userId));
//		if (empty($user)) {
//			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType . '/end/用户不存在-----------------------------------------------------------------');
//			return;
//		}
//		$czyUserId = intval($user['czyUserId']);
//		$czyPhone = $user['phone'];
//		$czyPassword = $user['czyPassword'];
//		// 调用发卷接口
//		$result1 = null;
//		if ($this->awardType == 2) { // 彩饭票
//			$result1 = ServiceCommonCzy::giveFP($czyPhone, intval($award['price']), '双彩家年华活动-赠送彩饭票');
//		} elseif ($this->awardType == 3) { // 优惠券
//			$result1 = ServiceCommonCzy::giveCoupon($czyUserId, $czyPhone, $czyPassword);
//		}
//		if (empty($result1) || $result1['code']) {
//			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType . '/end/发卷失败/' . $result1['code'] . '/' . $result1['message'] . '-----------------------------------------------------------------');
//			return;
//		}
//		// 修改卷的状态
//		$result2 = ManageActivityUseRecord::updateStatuses($this->activityUseRecordId, 3, 0);
//		if (!is_numeric($result2)) {
//			Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType . '/end/使用记录的状态更新失败-----------------------------------------------------------------');
//			return;
//		}
//		//
//		Log::info(__CLASS__ . '->' . __FUNCTION__ . ': SendAwardJob/' . $this->activityUseRecordId . '/' . $this->awardType . '/end-----------------------------------------------------------------');
	}
}
