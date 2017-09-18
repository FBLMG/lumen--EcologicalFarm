// 重构方法 					--------------------------------------------------------------------------------
/**
 * API请求失败结果处理
 * @param code
 * @param message
 * @param result
 * @returns {boolean} true：执行结束，false：执行API的failed回调
 */
$wk.api.resultFailedHandle = function (code, message, result) {
	if (code == 43002 || code == 1001 || code == 40102) {
	//if (true) {
		// 认证失败：跳转到登录页面
		window.location.href = $wk.page.href('login');
		return true;
	}
	$wk.api.hideLoading();
	return false;
};


/**
 * 保存用户身份
 * @param userId
 * @param userToken
 * @param userPower
 * @private
 */
$wk.api.saveUserInfo = function (userId, userToken, userPower) {
	$wk.cookie.set('__USER_ID__', userId, 24*7);
	$wk.cookie.set('__USER_TOKEN__', userToken, 24*7);
	$wk.cookie.set('__USER_POWER__', userPower, 24*7);
};

/**
 * 获取用户ID
 * @returns {*}
 * @private
 */
$wk.api.getUserId = function () {
	return $wk.cookie.get('__USER_ID__');
};

/**
 * 获取用户Token
 * @returns {*}
 * @private
 */
$wk.api.getUserToken = function () {
	return $wk.cookie.get('__USER_TOKEN__');
};

/**
 * 获取用户权限
 * @returns {*}
 * @private
 */
$wk.api.getUserPower = function () {
	return $wk.cookie.get('__USER_POWER__');
};

/**保存当前选择的微信公众号id
 * @returns {*}
 * @private
 */
$wk.api.saveWxSaId = function (wxSaId) {
	$wk.cookie.set('__WX_SA_ID__', wxSaId, 24*7);
};

/**
 * 获取当前选择的微信公众号id
 * @returns {*}
 * @private
 */
$wk.api.getWxSaId = function () {
	return $wk.cookie.get('__WX_SA_ID__');
};

// 封装网络请求方法 		----------------------------------------
/**
 * 格式化用户身份参数
 * @param params
 * @private
 */
$wk.api.formatUserParams = function (params) {
	var result = params || {};
	if (result) {
		result.adminId = parseInt(this.getUserId()) || 0;
		result.adminToken = this.getUserToken();
	} else {
		result = {
			adminId: parseInt(this.getUserId()) || 0,
			adminToken: this.getUserToken()
		};
	}
	//result.WxSaId = parseInt(this.getWxSaId()) || 0;
	return result;
};

/**
 * post请求
 * @param api
 * @param params
 * @param options
 */
$wk.api.post = function (api, params, options) {
	//params = this.formatReqTypeParams(params);
	params = this.formatUserParams(params);
	if (options == null) {
		options = {};
	}
	if (typeof options === 'function') {
		options = {success: options}
	}
	if (params.pageNumber !== undefined && params.pageNumber !== null && CONFIG_DEBUG === false) {
		api = api + '?page=' + (parseInt(params.pageNumber) + 1);
	}
	options.type = 'POST';
	this.request(api, params, options);
};

/**
 * post请求
 * @param api
 * @param params
 * @param options
 */
$wk.api.postSimple = function (api, params, options) {
	params = this.formatReqTypeParams(params);
	if (options == null) {
		options = {};
	}
	if (typeof options === 'function') {
		options = {success: options}
	}
	if (params.pageNumber !== undefined && params.pageNumber !== null && CONFIG_DEBUG === false) {
		api = api + '?page=' + (parseInt(params.pageNumber) + 1);
	}
	options.type = 'POST';
	this.request(api, params, options);
};

/**
 * delete请求
 * @param api
 * @param options
 */
$wk.api.del = function (api, options) {
	api = api + '?PUserId=' + (parseInt(this.getUserId()) || 0) + '&UserToken=' + this.getUserToken();
	if (options == null) {
		options = {};
	}
	if (typeof options === 'function') {
		options = {success: options}
	}
	options.type = 'DELETE';
	this.request(api, {}, options);
};

/**
 * put请求
 * @param api
 * @param params
 * @param options
 */
$wk.api.put = function (api, params, options) {
	params = this.formatUserParams(params);
	if (options == null) {
		options = {};
	}
	if (typeof options === 'function') {
		options = {success: options}
	}
	options.type = 'PUT';
	this.request(api, params, options);
};

/**
 * get请求
 * @param api
 * @param options
 */
$wk.api.get = function (api, options) {
	if (options == null) {
		options = {};
	}
	if (typeof options === 'function') {
		options = {success: options}
	}
	options.type = 'GET';
	this.request(api, {}, options);
};

/**
 * gets请求
 * @param api
 * @param params
 * @param options
 */
$wk.api.gets = function (api, params, options) {
	params = params || {};
	params.PUserId = parseInt(this.getUserId()) || 0;
	params.UserToken = this.getUserToken();

	if (options == null) {
		options = {};
	}
	if (typeof options === 'function') {
		options = {success: options}
	}
	options.type = 'GET';
	this.request(api, params, options);
};


// API						--------------------------------------------------------------------------------


/**
 * 3.5.2.企业用户登陆/v1/com/aaa/login
 */
$wk.api.aaa_login = function(name, password, options) {
	var api = "login";
	var parameterObj = {
		username: name,
		password: password
	};
	this.post(api, parameterObj, options);
}


/**
 * 3.5.3.企业用户注册/v1/com/aaa/doRegister
 */
$wk.api.aaa_doRegister = function(name, password, showName, seqId, checkCode, options) {
	var api = "com/aaa/doRegister";
	var parameterObj = {
		ComUser:　{
			Name: name,
			Password: password,
			ShowName: showName
		},
		SeqId: parseInt(seqId),
		CheckCode: checkCode
	}
	this.postSimple(api, parameterObj, options);
}


/**
 * 3.5.4.云平台用户注销/v1/com/aaa/logout
 */
$wk.api.aaa_loginout = function(id, token, options) {
	var api = "com/aaa/logout";
	var parameterObj = {
		ComUser: {
			Id: parseInt(id),
			Token: token
		}
	}
	this.post(api, parameterObj, options);
}


/**
 * 3.5.5.企业用户修改密码/v1/com/aaa/changePasswd
 */
$wk.api.aaa_changePasswd = function(id, token, oldPasswd, newPasswd, options) {
	var api = "com/aaa/changePasswd";
	var parameterObj = {
		ComUser: {
			Id: parseInt(id),
			Token: token
		},
		OldPasswd: oldPasswd,
		NewPasswd: newPasswd
	}
	this.post(api, parameterObj, options);
}


/**
 * 3.5.6.获取企业用户信息/v1/com/aaa/getComUserInfo
 */
$wk.api.aaa_getComUserInfo =function(id, token, options) {
	var api = "com/aaa/getComUserInfo";
	var parameterObj = {
		ComUser: {
			Id: parseInt(id),
			Token: token
		}
	}
	this.post(api, parameterObj, options);
}
