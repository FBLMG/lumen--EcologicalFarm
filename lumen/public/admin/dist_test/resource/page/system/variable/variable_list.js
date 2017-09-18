/**
 * 变量列表
 */
$wk.page.createPage('variable_list', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			search: {
				group: '',
				name: '',
				pageNumber: 0,
				pageSize: 10
			},
			initSearchStatus: 0,
			statusOptions: [{
				title: '全部状态',
				value: 0
			}, {
				title: '正常',
				value: 1
			}, {
				title: '禁用',
				value: 2
			}, {
				title: '草稿',
				value: 3
			}],
			typeOptions: [ {
				title: '发现',
				value: 1
			}, {
				title: '吐槽',
				value: 2
			}]
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="page-nav ui-nav">' +
			'			<div class="left">' +
			'               <a class="ui-btn ui-btn-success" onclick="$wk.page.load(\'variable_save\')" style="width:99px; height: 26px; line-height: 26px">新建变量</a>' +
			'			</div>' +
			'			<div class="right">' +
			'				<input class="ui-input" value="{{search.group}}" placeholder="输入group" onchange="{{$page}}.templateData.search.group=this.value;">' +
			'				<input class="ui-input" value="{{search.name}}" placeholder="输入name" onchange="{{$page}}.templateData.search.name=this.value;">' +
			'				<a class="ui-btn" onclick="{{$page}}.doSearch(event, this);">查询</a>' +
			'			</div>' +
			'		</div>' +
			'		{{if vaItems}}' +
			'		<div class="page-table ui-table ui-table-hover">' +
			'			<div class="ui-th">' +
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">ID</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:140px;">' +
			'					<div class="ui-con">变量group</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con">name</div>' +
			'				</div>' +


			'				<div class="ui-td" style="width:180px;">' +
			'					<div class="ui-con">描述</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">value</div>' +
			'				</div>' +'				' +
			'				<div class="ui-td" style="width:100px;">' +
			'					<div class="ui-con">操作</div>' +
			'				</div>' +
			'			</div>' +
			'			{{each vaItems}}' +
			'			<div class="ui-tr">' +
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">{{$value.id }}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:140px;">' +
			'					<div class="ui-con ui-l16">{{$value.group}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con">' +
			'						<textarea readonly style="width:100%;height:65px;resize: vertical;line-height: 18px;">{{$value.name}}</textarea>' +
			'					</div>' +
			'				</div>' +


			'				<div class="ui-td" style="width:180px;">' +
			'					<div class="ui-con">{{$value.desc}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">' +
			'						<textarea readonly style="width:100%;height:65px;resize: vertical;line-height: 18px;">{{$value.value}}</textarea>' +
			'					</div>' +
			//'					<div class="ui-con">{{$value.value}}</div>' +
			'				</div>' +'' +
			'				<div class="ui-td" style="width:100px;">' +
			'					<div class="ui-con ui-btns">' +
			'						<a onclick="{{$page}}.doEdit({{$value.id}})">编辑</a>' +
			'						{{if $value.status==3 || $value.status==0}}' +
			'						<a onclick="{{$page}}.doStatus({{$value.id}}, 1, \'恢复\')">正常</a>' +
			'						<a onclick="{{$page}}.doStatus({{$value.id}}, 2, \'禁止\')">禁止</a>' +
			'						{{else if $value.status==2}}' +
			'						<a onclick="{{$page}}.doStatus({{$value.id}}, 1, \'恢复\')">正常</a>' +
			'						{{else if $value.status==1}}' +
			'						<a onclick="{{$page}}.doStatus({{$value.id}}, 2, \'禁止\')">禁止</a>' +
			'						{{/if}}' +
			'						<a onclick="{{$page}}.doDelete({{$value.id}})">删除</a>' +
			'					</div>' +
			'				</div>' +
			'			</div>' +
			'			{{/each}}' +
			'			{{if vaItems.length==0}}' +
			'			<div class="ui-tr ui-tr-nohover">' +
			'				<div class="page-empty">没有任何数据</div>' +
			'			</div>' +
			'			{{/if}}' +
			'		</div>' +
			'		<div class="page-btns">' +
			'			<div class="left">' +
			'			</div>' +
			'			<div class="right">{{#PAGE}}</div>' +
			'		</div>' +
			'		{{else}}' +
			'		<div class="page-loading">正在加载中...</div>' +
			'		{{/if}}' +
			'	</div>' +
			'</div>' +
			'';
	},
	load: function () {
		var that = this;
		that.templateData.initSearchStatus = parseInt(this.params[0] || 0);
		if( that.templateData.initSearchStatus == 0 ){
			that.initSearch();
		}
	},
	loaded: function () {
		var that = this;
		that.drawView(that.parseTemplate(that.template, that.templateData));
		that.getVaList(function (result) {
			console.log(result);
			that.templateData.vaItems = result.data.dataList;
			that.templateData.PAGE = $wk.pagination.eachHtml('variable_list',
				(that.templateData.search.pageNumber + 1),
				that.templateData.search.pageSize,
				result.data.dataCount,
				5,
				function (pageIndex) {
					that.templateData.search.pageNumber = pageIndex - 1;
					that.drawSearch();
				}
			);
			that.drawView(that.parseTemplate(that.template, that.templateData));

		});

	}
}, {
	/**
	 * 获取变量列表
	 * @param callback
	 */
	getVaList: function (callback) {
		var that = this;
		$wk.api.variableSystem.getList(
			that.templateData.search.group,
			that.templateData.search.name,
			that.templateData.search.pageNumber,
			that.templateData.search.pageSize,
			function (result) {
				callback && callback(result)
			}
		);
	},
	/**
	 * 初始化查询参数
	 */
	initSearch: function () {
		this.templateData.search = {
			group:'',
			name:'',
			pageNumber: 0,
			pageSize: 10
		};
	},
	/**
	 * 重绘查询数据
	 */
	drawSearch: function () {
		var that = this;
		that.templateData.vaItems = null;
		that.drawView(that.parseTemplate(that.template, that.templateData));
		//that.getVaList(function (resultVa) {
		//	that.templateData.vaItems = resultVa.data.dataList;
		//	that.drawView(that.parseTemplate(that.template, that.templateData));
		//});

		that.getVaList(function (result) {
			that.templateData.vaItems = result.data.dataList;
			that.templateData.PAGE = $wk.pagination.eachHtml('variable_list',
				(that.templateData.search.pageNumber + 1),
				that.templateData.search.pageSize,
				result.data.dataCount,
				5,
				function (pageIndex) {
					that.templateData.search.pageNumber = pageIndex - 1;
					that.drawSearch();
				}
			);
			that.drawView(that.parseTemplate(that.template, that.templateData));



		});
	},
	/**
	 * 执行查询
	 */
	doSearch: function () {
		this.drawSearch();
	},
	/**
	 * 执行编辑
	 * @param id
	 */
	doEdit: function (id) {
		$wk.page.load('variable_save', [id]);
	},
	/**
	 * 执行状态
	 * @param id
	 * @param status
	 * @param msg
	 */
	doStatus: function (id, status, msg) {
		var that = this;
		$wk.msg.showConfirm('确定要' + msg + '变量？', function () {
			$wk.api.variableSystem.editStatus(id, status, function () {
				that.pageLoaded();
			});
		});
	},
	/**
	 * 执行删除
	 * @param id
	 */
	doDelete: function (id) {
		var that = this;
		$wk.msg.showConfirm('确定要删除变量？', function () {
			$wk.api.variableSystem.del(id, function () {
				that.pageLoaded();
			});
		});
	}
}));

