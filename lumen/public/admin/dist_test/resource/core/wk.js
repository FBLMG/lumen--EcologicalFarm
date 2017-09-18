/**
 * 悟空
 * @type {{}}
 */
var $wk = {};

/**
 * 交换数组元素
 * @param array
 * @param index1
 * @param index2
 * @returns {$wk}
 */
$wk.swapItem = function (array, index1, index2) {
	array[index1] = array.splice(index2, 1, array[index1])[0];
	return this;
};

/**
 * 是否在数组内
 * @param item
 * @param array
 * @returns {boolean}
 */
$wk.inArray = function (item, array) {
	for (var a in array) {
		if (item == array[a]) {
			return true;
		}
	}
	return false;
};

/**
 * 获取URL参数
 * @param name
 * @returns {*}
 */
$wk.getUrlParam = function (name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null) return unescape(r[2]);
	return null;
};