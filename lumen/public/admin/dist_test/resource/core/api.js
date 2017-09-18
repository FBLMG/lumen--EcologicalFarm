/**
 * API封装
 * @depend config.js
 */
$wk.api = {};

/**
 * 是否自动显示加载中的提示并自动关闭
 * @type {boolean}
 */
$wk.api.__isAutoShowLoading = CONFIG_API_AUTO_SHOW_LOADING;

// ---------------------------------------------------------------------------------------------------

/**
 * 获取完整的API地址
 * @param url
 * @returns {*}
 * @private
 */
$wk.api.api = function (url) {
	return CONFIG_API_HTTP + url;
};

/**options
 * POST请求
 * @param api API名称
 * @param params 参数
 * @param options 属性 {type|timeout|start|success|failed|error|complete}
 * @private
 */
$wk.api.request = function (api, params, options) {
	var that = this;
	if (options === undefined) {
		options = function () {
		};
	}
	if (typeof options.start === 'function') {
		options.start();
	} else {
		if (that.__isAutoShowLoading) {
			$wk.msg.showLoading('正在加载中...');
		}
	}
	$.ajax({
		type: options.type || 'POST',
		url: that.api(api),
		contentType: 'application/json; charset=utf-8',
		data: options.type === 'GET' ? params : JSON.stringify(params),
		dataType: 'json',
		timeout: options.timeout || 60 * 1000,
		success: function (result) {
			//console.log(result);
			var code = parseInt(result.code);
			var message = result.message || '请求出错';
			if (code === 0) {
				if (that.resultSuccessHandle(result) === false) {
					if (typeof options === 'function') {
						options(result);
					} else if (typeof options.success === 'function') {
						options.success(result);
					}
				}
			} else if (that.resultFailedHandle(code, message, result) === false) {
				if (typeof options.failed === 'function') {
					options.failed(result);
				} else {
					$wk.msg.showAlert(message);
				}
			}
		},
		error: function (xhr) {
			if (typeof options.error === 'function') {
				options.error(xhr);
			} else {
				$wk.msg.showAlert('网络错误，请稍后重试');
			}
		},
		complete: function (xhr, status) {
			if (status == 'timeout') {
				$wk.msg.showAlert('网络超时，请稍后重试！');
				return;
			}
			if (typeof options.complete === 'function') {
				options.complete(xhr);
			} else {
					$wk.msg.hideLoading();
				if (that.__isAutoShowLoading) {
				}
			}
		}
	});
};

/**
 * 成功结果处理
 * @param result
 * @returns {boolean} true：执行结束，false：执行API的success回调
 */
$wk.api.resultSuccessHandle = function (result) {
	return false;
};

/**
 * 失败结果处理
 * @param code
 * @param message
 * @param result
 * @returns {boolean} true：执行结束，false：执行API的failed回调
 */
$wk.api.resultFailedHandle = function (code, message, result) {
	return false;
};

/**
 * 格式化ReqType
 * @param params
 * @private
 */
$wk.api.formatReqTypeParams = function (params) {
	var result = params || {};
	result.ReqType = CONFIG_API_REQTYPE;
	result.AppKey = CONFIG_API_KEY;
	return result;
};

/**
 * 格式化设备参数
 * @param params
 * @private
 */
$wk.api.formatMobileInfoParams = function (params) {
	var result = params || {};
	result.MI = {
		AppVersion: '1.0',
		Os: 'WeiXing',
		MobileModel: '微信',
		IMSI: '',
		IMEI: ''
	};
	return result;
};

/**
 * 格式化用户身份参数
 * @param params
 * @private
 */
$wk.api.formatUserParams = function (params) {
	var result = params || {};
	if (result) {
		result.adminId = parseInt(this.getUserId());
		result.adminToken = this.getUserToken();
	} else {
		result = {
			adminId: parseInt(this.getUserId()),
			adminToken: this.getUserToken()
		};
	}
	return result;
};

/**
 * 保存用户身份
 * @param userId
 * @param userToken
 * @private
 */
$wk.api.saveUserInfo = function (userId, userToken) {
	$wk.cookie.set('__USER_ID__', userId, 1);
	$wk.cookie.set('__USER_TOKEN__', userToken, 1);
};

/**
 * 删除用户身份
 * @private
 */
$wk.api.removeUserInfo = function () {
	$wk.cookie.remove('__USER_ID__');
	$wk.cookie.remove('__USER_TOKEN__');
};

/**
 * 获取用户ID
 * @returns {*}
 * @private
 */
$wk.api.getUserId = function () {
	return $wk.cookie.get('__USER_ID__') || CONFIG_API_USER_ID;
};

/**
 * 获取用户Token
 * @returns {*}
 * @private
 */
$wk.api.getUserToken = function () {
	return $wk.cookie.get('__USER_TOKEN__') || CONFIG_API_USER_TOKEN;
};

/**
 * 显示加载中
 * @param title
 */
$wk.api.showLoading = function (title) {
	this.__isAutoShowLoadingTemp = this.__isAutoShowLoading;
	this.__isAutoShowLoading = false;
	$wk.msg.showLoading(title || '正在加载中...');
	return this;
};

/**
 * 隐藏加载中
 */
$wk.api.hideLoading = function () {
	this.__isAutoShowLoading = this.__isAutoShowLoadingTemp;
	$wk.msg.hideLoading();
	return this;
};