/**
 * 微信公众号广告管理
 */
$wk.api.variableSystem = {
	getApi: 'variableGet',
	getListApi: 'variableGetList',
	addApi: 'variableInsert',
	editApi: 'variableUpdate',
	deleteApi: 'variableDelete',
	editStatusApi: 'adUpdateStatus',

	get: function(id, options){
		$wk.api.post(this.getApi, {id:parseInt(id)}, options);
	},
	getList:function (group,name, pageNumber, pageSize, options)  {
		$wk.api.post(this.getListApi, {
			group: group,
			name: name,
			pageNumber: pageNumber,
			pageSize: parseInt(pageSize)
		}, options);
	},
	add: function(va, options){
		$wk.api.post(this.addApi, va, options);
	},
	edit: function(va, options){
		$wk.api.post(this.editApi, va, options);
	},
	del: function(id, options){
		$wk.api.post(this.deleteApi, {id:id}, options);
	},
	editStatus: function(id, status, options){
		$wk.api.post(this.editStatusApi, {
			id: parseInt(id),
			status: parseInt(status)
		}, options);
	}
};
