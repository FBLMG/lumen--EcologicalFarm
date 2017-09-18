/**
 * 清除缓存
 */
$wk.api.cache = {
	cleanCache: function (options) {
		$wk.api.post('cleanCache', {}, options);
	}
};
