/**
 * 分页
 * @type {{}}
 */
$wk.pagination = {};

/**
 * 配置
 * @type {{}}
 */
$wk.pagination.config = {};

/**
 * 输出HTML
 * @param id
 * @param pageIndex 当前第几页
 * @param pageSize 每页显示数量
 * @param pageTotal 数据总数
 * @param showNumber
 * @param callback
 * @returns {string}
 */
$wk.pagination.eachHtml = function (id, pageIndex, pageSize, pageTotal, showNumber, callback) {
	this.config[id] = {
		pageIndex: pageIndex,
		pageSize: pageSize,
		pageTotal: pageTotal,
		showNumber: showNumber,
		callback: callback
	};
	var config = this.handleConfig(id);
	var html = '';
	html += '<div class="ui-pagination" id="' + id + '">';
	html += '	<a class="prev" onclick="$wk.pagination.prev(\'' + id + '\');">上一页</a>';
	html += '	<a class="first' + (config.pageStart === 1 ? ' hide' : '') + '" onclick="$wk.pagination.goto(\'' + id + '\', 1);">1</a>';
	html += '	<a class="morep' + (config.pageStart > 1 ? '' : ' hide') + '">...</a>';
	for (var a = 1; a <= config.count; a++) {
		html += '	<a class="item' + (a >= config.pageStart && a <= config.pageEnd ? '' : ' hide') + (a === pageIndex ? ' on' : '') + '" onclick="$wk.pagination.goto(\'' + id + '\', ' + a + ');">' + a + '</a>';
	}
	html += '	<a class="moren' + (config.pageEnd < config.count ? '' : ' hide') + '">...</a>';
	html += '	<a class="last' + (config.pageEnd === config.count ? ' hide' : '') + '" onclick="$wk.pagination.goto(\'' + id + '\', ' + config.count + ');">' + config.count + '</a>';
	html += '	<a class="stat"><input type="text" value="' + config.pageIndex + '" onkeyup="$wk.pagination.keyup(event, this, \'' + id + '\');"/>/' + config.count + '页</a>';
	html += '	<a class="next" onclick="$wk.pagination.next(\'' + id + '\');">下一页</a>';
	html += '</div>';
	return html;
};

/**
 * 处理配置
 * @param id
 * @returns {*}
 */
$wk.pagination.handleConfig = function (id) {
	var config = this.config[id];
	config.count = Math.ceil(config.pageTotal / config.pageSize);
	config.pageStart = config.pageIndex - parseInt(config.showNumber / 2);
	config.pageEnd = config.pageIndex + parseInt(config.showNumber / 2);
	if (config.pageStart < 1) {
		config.pageStart = 1;
	}
	if (config.pageEnd > config.count) {
		config.pageEnd = config.count;
	}
	return config;
};

$wk.pagination.keyup = function (e, obj, id) {
	if (e.keyCode === 13) { // 回车
		$wk.pagination.goto(id, parseInt(obj.value));
	}
};

/**
 * 刷新
 * @param id
 */
$wk.pagination.refresh = function (id) {
	var config = this.handleConfig(id);
	var $prev = $('#' + id + ' > .prev');
	var $first = $('#' + id + ' > .first');
	var $morep = $('#' + id + ' > .morep');
	var $moren = $('#' + id + ' > .moren');
	var $last = $('#' + id + ' > .last');
	var $stat = $('#' + id + ' > .stat');
	var $next = $('#' + id + ' > .next');
	if (config.pageStart === 1) {
		$first.addClass('hide');
	} else {
		$first.removeClass('hide');
	}
	if (config.pageStart > 1) {
		$morep.removeClass('hide');
	} else {
		$morep.addClass('hide');
	}
	if (config.pageEnd < config.count) {
		$moren.removeClass('hide');
	} else {
		$moren.addClass('hide');
	}
	if (config.pageEnd === config.count) {
		$last.addClass('hide');
	} else {
		$last.removeClass('hide');
	}
	$stat.html('<input type="text" value="' + config.pageIndex + '" onblur="$wk.pagination.goto(\'' + id + '\', parseInt(this.value));"/>/' + config.count + '页');
	$('#' + id + ' > .item').each(function (index) {
		if (index + 1 >= config.pageStart && index + 1 <= config.pageEnd) {
			$(this).removeClass('hide');
		} else {
			$(this).addClass('hide');
		}
		if (index + 1 === config.pageIndex) {
			$(this).addClass('on');
		} else {
			$(this).removeClass('on');
		}
	});
};

/**
 * 上一页
 * @param id
 */
$wk.pagination.prev = function (id) {
	var config = this.config[id];
	if (config.pageIndex <= 1) {
		return;
	}
	config.pageIndex--;
	this.refresh(id);
	config.callback && config.callback(config.pageIndex);
};

/**
 * 下一页
 * @param id
 */
$wk.pagination.next = function (id) {
	var config = this.config[id];
	if (config.pageIndex >= config.count) {
		return;
	}
	config.pageIndex++;
	this.refresh(id);
	config.callback && config.callback(config.pageIndex);
};

/**
 * 跳转至目标页面
 * @param id
 * @param pageIndex
 */
$wk.pagination.goto = function (id, pageIndex) {
	var config = this.config[id];
	if (pageIndex < 1 || pageIndex > config.count) {
		$wk.msg.showAlert('请输入1-' + config.count + '的数字');
		return;
	}
	config.pageIndex = pageIndex;
	this.refresh(id);
	config.callback && config.callback(config.pageIndex);
};