/**
 * 系统工具
 * @type {{}}
 */
var $system = {};

/**
 * 显示空页面
 */
$system.showEmptyView = function (status, page, msg) {
	if (status) {
		$(page.elementNode).html('<div class="system-empty"><div class="box"><div class="icon iconpng"></div><div class="msg">' + (msg || '暂时没有数据') + '</div></div></div>');
	}
};

/**
 * 颜色
 * @param input
 * @returns {*}
 */
$system.color = function (input) {
	var len = 6 - input.length;
	var a = 0;
	var result = input;
	if (len < 0) {
		len = 0;
	}
	for (a; a < len; a++) {
		result += '0';
	}
	return result;
};

/**
 * 格式化图片地址
 * @param input
 * @param args
 * @returns {*}
 */
$system.image = function (input, args) {
	if (!input || typeof input != 'string') {
		return '';
	}
	if (args) {
		var img1 = input.substring(0, input.lastIndexOf('.'));
		var img2 = input.substring(input.lastIndexOf('.'));
		var width = args[0];
		var height = args[1];
		return img1 + '_' + width + 'x' + height + img2;
	} else {
		return input;
	}
};

/**
 * 格式化价格
 * @param input
 * @returns {string}
 */
$system.price = function (input) {
	return parseFloat(input).toFixed(2);
};

/**
 * 获取昵称
 * @param user
 * @returns {string}
 */
$system.nickname = function (user) {
	return user ? (user.WxInfo ? user.WxInfo.NickName : '无名氏') : '无名氏';
};

/**
 * 获取头像
 * @param user
 * @returns {string}
 */
$system.avatar = function (user) {
	return user.WxInfo ? user.WxInfo.Avatar : CONFIG_RESOURCE_HTTP + 'resource/img/user-icon@2x.png';
};

/**
 * 状态标签
 * @param status
 * @returns {*}
 */
$system.status1 = function (status) {
	return ['', '正常', '禁用', '草稿', '删除'][status];
};
$system.status2 = function (status) {
	return ['', '上架', '显示', '下架', '删除'][status];
};
$system.statusPay = function (status) {
	return ['', '未支付', '正在支付', '支付成功', '支付失败', '删除'][status];
};

/**
 * 模板辅助方法
 */
template.helper('color', function (input, args) {
	return $system.color(input, args);
});
template.helper('image', function (input, args) {
	return $system.image(input, args);
});
template.helper('price', function (input) {
	return $system.price(input);
});
template.helper('nickname', function (input) {
	return $system.nickname(input);
});
template.helper('avatar', function (input) {
	return $system.avatar(input);
});
template.helper('date', function (input) {
	return $wk.date.date(input);
});
template.helper('datetime', function (input) {
	return $wk.date.datetime(input);
});
template.helper('status1', function (input) {
	return $system.status1(input);
});
template.helper('status2', function (input) {
	return $system.status2(input);
});
template.helper('statusPay', function (input) {
	return $system.statusPay(input);
});


//判断浏览器
(function () {
	window.browser = {};
	if (navigator.userAgent.indexOf("MSIE") > 0) {
		browser.name = 'MSIE';
		browser.ie = true;
	} else if (navigator.userAgent.indexOf("Firefox") > 0) {
		browser.name = 'Firefox';
		browser.firefox = true;
	} else if (navigator.userAgent.indexOf("Chrome") > 0) {
		browser.name = 'Chrome';
		browser.chrome = true;
	} else if (navigator.userAgent.indexOf("Safari") > 0) {
		browser.name = 'Safari';
		browser.safari = true;
	} else if (navigator.userAgent.indexOf("Opera") >= 0) {
		browser.name = 'Opera';
		browser.opera = true;
	} else {
		browser.name = 'unknow';
	}
})();