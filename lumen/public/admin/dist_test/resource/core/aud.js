/**
 * 图片
 * @type {{}}
 */
$wk.aud = {};

/**
 * 获取多个文件
 * @param files
 * @param callback
 * @returns {$wk.aud}
 */
$wk.aud.getFiles = function (files, callback) {
	var errorNumber = 0;
	var completeNumber = 0;
	var results = [];
	for (var a in files) {
		if (typeof files[a] !== 'object') {
			continue;
		}
		this.getFile(files[a], function (result) {
			completeNumber++;
			if (result === false) {
				errorNumber++;
			} else {
				results.push(result);
			}
			if (completeNumber === files.length) {
				if (errorNumber) {
					$wk.msg.showAlert('总共上传' + completeNumber + '首声音文件，其中' + errorNumber + '首声音文件格式不正确');
				}
				callback && callback(results);
			}
		});
	}
	return this;
};

/**
 * 获取一个文件
 * @param file
 * @param callback
 * @returns {$wk.aud}
 */
$wk.aud.getFile = function (file, callback) {
	if (!/audio\/\w+/.test(file.type)) {
		callback && callback(false);
	}
	var reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = function () {
		callback && callback(this.result);
	};
	return this;
};

/**
 * 处理数据
 * @param blob
 * @param obj
 * @returns {$wk.aud}
 */
$wk.aud.handleData = function (blob, obj) {
	var img = new Image();
	img.src = blob;
	img.onload = function () {
		var that = this;

		if (obj.width >= that.width) {
			obj.success({
				base64: blob,
				clearBase64: blob.substr(blob.indexOf(',') + 1)
			});
			return;
		}

		//生成比例
		var w = that.width,
			h = that.height,
			scale = w / h;
		w = obj.width || w;
		h = w / scale;

		//生成canvas
		var canvas = document.createElement('canvas');
		var ctx = canvas.getContext('2d');
		$(canvas).attr({
			width: w,
			height: h
		});
		ctx.drawImage(that, 0, 0, w, h);

		/**
		 * 生成base64
		 */
		var base64 = canvas.toDataURL('image/jpeg', obj.quality || 0.8);

		// 生成结果
		var result = {
			base64: base64,
			clearBase64: $wk.aud.clearBase64(base64)
		};

		// 执行后函数
		obj.success(result);
	};
	return this;
};

/**
 * 清理Base64信息
 * @param data
 * @returns {string|*}
 */
$wk.aud.clearBase64 = function (data) {
	return data.substr(data.indexOf(',') + 1);
};