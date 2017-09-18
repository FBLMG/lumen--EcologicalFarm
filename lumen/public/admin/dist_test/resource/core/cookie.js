/**
 * Cookie
 * @type {{}}
 */
$wk.cookie = {};

/**
 * 设置
 * @param name
 * @param value
 * @param expHour
 * @param domain
 * @param path
 */
$wk.cookie.set = function (name, value, expHour, domain, path) {
	document.cookie = name + "=" + encodeURIComponent(value == undefined ? "" : value) + (expHour ? "; expires=" + new Date(new Date().getTime() + (expHour - 0) * 3600000).toUTCString() : "") + "; domain=" + (domain ? domain : document.domain) + "; path=" + (path ? path : "/");
	return this;
};

/**
 * 获取
 * @param name
 * @returns {*}
 */
$wk.cookie.get = function (name) {
	return document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)")) == null ? null : decodeURIComponent(RegExp.$2);
};

/**
 * 删除
 * @param name
 */
$wk.cookie.remove = function (name) {
	if (this.get(name) != null) this.set(name, null, -1);
	return this;
};