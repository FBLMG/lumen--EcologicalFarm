/**
 * 访问统计
 */
$wk.api.total = {
	totalRecordGetList: function (search, options) {
		$wk.api.post('totalRecordGetList', {
			userId: parseInt(search.userId || 0),
			pname: search.pname || '',
			ptitle: search.ptitle || '',
			startTime: parseInt(search.startTime || 0),
			endTime: parseInt(search.endTime || 0),
			pageSize: parseInt(search.pageSize || 0),
			pageNumber: parseInt(search.pageNumber || 0)
		}, options);
	},
};
