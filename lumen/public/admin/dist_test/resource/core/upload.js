/**
 * 上传
 * @type {{}}
 */
$wk.upload = {};

/**
 * 上传文件
 * @param api API
 * @param data 参数
 * @param callback 回调 {start|progress|success|failed|error|complete}
 * @returns {$wk.upload}
 */
$wk.upload.file = function (api, data, callback) {
	var xmlhttp, timer;
	if (this.browser == 'MSIE') {
		try {
			xmlhttp = new ActiveXObject('microsoft.xmlhttp');
		} catch (e) {
			xmlhttp = new ActiveXObject('msxml2.xmlhttp');
		}
	} else {
		xmlhttp = new XMLHttpRequest();
	}
	if (typeof callback.start === 'function') {
		callback.start();
	}
	timer = setTimeout(function () {
		xmlhttp.abort('timeout');
	}, 30 * 1000); // 超时时间
	xmlhttp.upload.onprogress = function (event) {
		if (event.lengthComputable) {
			callback && callback.progress(parseInt(event.loaded / event.total * 100));
		}
	};
	xmlhttp.onreadystatechange = function () {
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200) {
				eval('var result=' + xmlhttp.responseText);
				var code = parseInt(result.code);
				if (code == 0) {
					if (typeof callback.success === 'function') {
						callback.success(result);
					}
				} else {
					if (typeof callback.failed === 'function') {
						callback.failed(result);
					} else {
						$wk.msg.showAlert(result.data.errorMsg || '出错了');
					}
				}
			} else {
				if (typeof callback.error === 'function') {
					callback.error(xmlhttp);
				} else {
					$wk.msg.showAlert('网络错误，请稍后重试');
				}
			}
			if (xmlhttp.status == 'timeout') {
				$wk.msg.showAlert('网络超时，请稍后重试！');
			}
			if (typeof callback.complete === 'function') {
				callback.complete(xmlhttp);
			}
			clearTimeout(timer);
		}
	};
	xmlhttp.open('POST', api, true);
	xmlhttp.send(JSON.stringify(data));
	return this;
};