/**
 * 核心
 * @depend zepto.js
 * @type {{}}
 */
$wk.msg = {};

$wk.msg.__alertCallback = null;
$wk.msg.__confirmCallback = null;
$wk.msg.__frameCallback = null;

/**
 * 显示加载框
 * @param title
 * @returns {$wk.msg}
 */
$wk.msg.showLoading = function (title) {
	if (!$('#msg_loading').get(0)) {
		$('body').append('<div id="msg_loading" class="msg_loading"><div class="title">' + title + '</div></div>');
	}
	return this;
};

/**
 * 隐藏加载框
 * @returns {$wk.msg}
 */
$wk.msg.hideLoading = function () {
	setTimeout(function () {
		$('#msg_loading').remove();
	}, 500);
	return this;
};


/**
 * 显示提示
 * @param title
 * @param callback
 * @param buttonTitle
 * @returns {$wk.msg}
 */
$wk.msg.showAlert = function (title, callback, buttonTitle) {
	if (!$('#msg_alert').get(0)) {
		$('body').append('' +
			'<div id="msg_alert" class="msg_alert">' +
			'	<div class="box">' +
			'		<div class="close" onclick="$wk.msg.hideAlert();">x</div>' +
			'		<div class="title">' + title + '</div>' +
			'		<div class="button">' +
			'			<a onclick="$wk.msg.hideAlert();">' + (buttonTitle ? buttonTitle : '确定') + '</a>' +
			'		</div>' +
			'	</div>' +
			'</div>');
		this.__alertCallback = callback;
	}
	return this;
};

/**
 * 隐藏提示
 * @returns {$wk.msg}
 */
$wk.msg.hideAlert = function () {
	if (this.__alertCallback && this.__alertCallback() === false) {
		return this;
	}
	$('#msg_alert').remove();
	return this;
};

/**
 * 显示确认框
 * @param title
 * @param callback
 * @param buttonTitle
 * @returns {$wk.msg}
 */
$wk.msg.showConfirm = function (title, callback, buttonTitle) {
	if (!$('#msg_confirm').get(0)) {
		$('body').append('' +
			'<div id="msg_confirm" class="msg_confirm">' +
			'	<div class="box">' +
			'		<div class="title">' + title + '</div>' +
			'		<div class="button">' +
			'			<a class="no" onclick="$wk.msg.hideConfirm(2);">' + (buttonTitle && buttonTitle.no ? buttonTitle.no : '取消') + '</a>' +
			'			<a class="yes" onclick="$wk.msg.hideConfirm(1);">' + (buttonTitle && buttonTitle.yes ? buttonTitle.yes : '确定') + '</a>' +
			'		</div>' +
			'	</div>' +
			'</div>');
		this.__confirmCallback = callback;
	}
	return this;
};

/**
 * 隐藏确认框
 * @param operate
 * @returns {$wk.msg}
 */
$wk.msg.hideConfirm = function (operate) {
	if (operate === 1) { // 确定
		if (typeof this.__confirmCallback === 'function' && this.__confirmCallback() === false) {
			return this;
		} else if (typeof this.__confirmCallback === 'object' && typeof this.__confirmCallback.yes === 'function' && this.__confirmCallback.yes() === false) {
			return this;
		}
	} else if (operate === 2) { // 取消
		if (this.__confirmCallback && this.__confirmCallback.no && this.__confirmCallback.no() === false) {
			return this;
		}
	}
	$('#msg_confirm').remove();
	return this;
};


/**
 * 显示Frame
 * @param title
 * @param url
 * @param width
 * @param height
 * @param callback
 * @returns {$wk.msg}
 */
$wk.msg.showFrame = function (title, url, width, height, callback) {
	if (!$('#msg_frame').get(0)) {
		$('body').append('' +
			'<div id="msg_frame" class="msg_frame">' +
			'	<div class="box">' +
			'		<div class="close" onclick="$wk.msg.hideFrame();">x</div>' +
			'		<div class="title">' + title + '</div>' +
			'		<div class="frame" style="width: ' + width + 'px;height: ' + height + 'px;">' +
			'			<iframe src="' + url + '" style="width: 100%;height: 100%;border: none;"></iframe>' +
			'		</div>' +
			'	</div>' +
			'</div>');
		this.__frameCallback = callback;
	}
	return this;
};

/**
 * 隐藏Frame
 * @returns {$wk.msg}
 */
$wk.msg.hideFrame = function () {
	$('#msg_frame').remove();
	return this;
};