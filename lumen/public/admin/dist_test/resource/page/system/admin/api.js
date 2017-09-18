/**
 * 管理员管理
 */
$wk.api.adminSystem = {
	// 获取管理者详情
	adminGet: function (id, options) {
		$wk.api.post('adminGet', {id: parseInt(id)}, options);
	},
	// 获取管理者列表
	adminGetList: function (search, options) {
		$wk.api.post('adminGetList', {
			username: search.username,
			status: parseInt(search.status),
			startAt: parseInt(search.startAt),
			endAt: parseInt(search.endAt),
			pageNumber: parseInt(search.pageNumber),
			pageSize: parseInt(search.pageSize)
		}, options);
	},
	// 添加管理者
	adminInsert: function (insert, options) {
		$wk.api.post('adminInsert', {
			username: insert.username,
			password: insert.password,
		}, options);
	},
	// 编辑管理者
	adminUpdate: function (update, options) {
		$wk.api.post('adminUpdate', {
			id: parseInt(update.id),
			username: update.username,
			password: update.password

		}, options);
	},
	// 移除管理者
	adminDelete: function (id, options) {
		$wk.api.post('adminDelete', {id: parseInt(id)}, options);
	},
	// 启用管理者
	adminStart: function (id,options) {
		$wk.api.post('adminStart', {id: parseInt(id)}, options);
	},
	// 禁用管理者
	adminStop: function (id,options) {
		$wk.api.post('adminStop', {id: parseInt(id)}, options);
	}
};
