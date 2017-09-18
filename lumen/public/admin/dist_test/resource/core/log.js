/**
 * 日志
 * @param log
 */
$wk.log = function (log) {
	if (CONFIG_DEBUG) {
		console.log(log);
	}
	return this;
};