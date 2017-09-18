/**
 * 日期
 * @type {{}}
 */
$wk.date = {};

/**
 * 格式化时间数值
 * @param number
 * @returns {string}
 */
$wk.date.formatTimeNumber = function (number) {
	return parseInt(number) < 10 ? '0' + number : number;
};

/**
 * 格式化日期时间
 * @param datetime
 * @returns {string}
 */
$wk.date.formatDatetime = function (datetime) {
	if (!datetime) {
		return '';
	}
	datetime = datetime.replace(/-/g, '/');
	datetime = datetime.replace('T', ' ');
	datetime = datetime.replace('Z', '');
	if (parseInt(datetime.indexOf('+')) > -1) {
		datetime = datetime.substring(0, datetime.indexOf('+'));
	}
	if (parseInt(datetime.indexOf('.')) > -1) {
		datetime = datetime.substring(0, datetime.indexOf('.'));
	}
	var dt = datetime.split(' ');
	var date = dt[0] ? dt[0].split('/') : [];
	var time = dt[1] ? dt[1].split(':') : [];
	var year = parseInt(date[0]);
	var month = parseInt(date[1]);
	var day = parseInt(date[2]);
	var hour = parseInt(time[0]) || 0;
	var minute = parseInt(time[1]) || 0;
	var second = parseInt(time[2]) || 0;
	var result = year + '/' +
		this.formatTimeNumber(month) + '/' +
		this.formatTimeNumber(day) + ' ' +
		this.formatTimeNumber(hour) + ':' +
		this.formatTimeNumber(minute) + ':' +
		this.formatTimeNumber(second);
	if (isNaN(year) ||
		isNaN(month) ||
		isNaN(day) ||
		isNaN(hour) ||
		isNaN(minute) ||
		isNaN(second)) {
		result = '';
	}
	return result;
};

/**
 * 获取时间戳
 * @param datetime
 * @returns {number}
 */
$wk.date.unixtime = function (datetime) {
	if (datetime == null) {
		return parseInt(new Date().getTime() / 1000);
	}
	var dt = this.formatDatetime(datetime);
	if (dt == '') {
		return 0;
	}
	return parseInt(Date.parse(dt) / 1000);
};

/**
 * 格式化日期
 * @param nS
 * @returns {string}
 */
$wk.date.date = function (nS) {
	if (!nS) {
		return '';
	}
	var now = new Date(parseInt(nS) * 1000);
	var year = now.getFullYear();
	var month = now.getMonth() + 1;
	var date = now.getDate();
	return year + "-" + this.formatTimeNumber(month) + "-" + this.formatTimeNumber(date);

};

/**
 * 格式化时间
 * @param nS
 * @returns {string}
 */
$wk.date.time = function (nS) {
	if (!nS) {
		return '';
	}
	var now = new Date(parseInt(nS) * 1000);
	var hour = now.getHours();
	var minute = now.getMinutes();
	var second = now.getSeconds();
	return this.formatTimeNumber(hour) + ":" +
		this.formatTimeNumber(minute) + ":" +
		this.formatTimeNumber(second);
};

/**
 * 格式化日期和时间
 * @param nS
 * @returns {string}
 */
$wk.date.datetime = function (nS) {
	if (!nS) {
		return '';
	}
	var now = new Date(parseInt(nS) * 1000);
	var year = now.getFullYear();
	var month = now.getMonth() + 1;
	var date = now.getDate();
	var hour = now.getHours();
	var minute = now.getMinutes();
	var second = now.getSeconds();
	return year + "-" +
		this.formatTimeNumber(month) + "-" +
		this.formatTimeNumber(date) + " " +
		this.formatTimeNumber(hour) + ":" +
		this.formatTimeNumber(minute) + ":" +
		this.formatTimeNumber(second);

};