/**
 * 管理员列表
 */
$wk.page.createPage('admin-list', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			search: {
				name: '',
				status: 0,
				startAt: 0,
				endAt: 0,
				pageNumber: 0,
				pageSize: 10
			},
			statusLabel: ['', '正常', '禁用'],
			powerLabel: ['','超级管理员']
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="page-nav ui-nav">' +
			'			<div class="left">' +
			'               <a class="ui-btn ui-btn-success" onclick="$wk.page.load(\'admin-save\')" style="width:99px; height: 26px; line-height: 26px">新建管理员</a>' +
			'			</div>' +
			'		</div>' +
			'		{{if adminList}}' +
			'		<div class="page-table ui-table ui-table-hover">' +
			'			<div class="ui-th">' +
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">ID</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con">账户</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con">权限</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con">状态</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:140px;">' +
			'					<div class="ui-con">创建时间</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con">操作</div>' +
			'				</div>' +
			'			</div>' +
			'			{{each adminList}}' +
			'			<div class="ui-tr">' +
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">{{$value.id}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con">{{$value.username}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con ui-l16">{{powerLabel[$value.power]}}</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con ui-l16">{{statusLabel[$value.status]}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:140px;">' +
            '                   <div class="ui-con" title="{{$value.createAt | datetime}}">'+
            '                       {{if $value.createAt}}'+
            '                       <p style="margin:0;">{{$value.createAt | datetime}}</p>'+
            '                       {{/if}}'+
            '                   </div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con">' +
			'						<a onclick="{{$page}}.doEdit({{$value.id}})">编辑</a>' +	
			'                       {{if $value.status==2}}'+		
			'						<a onclick="{{$page}}.doStart({{$value.id}})">启用</a>' +
			'                       {{else if $value.status==1}}'+		
			'						<a onclick="{{$page}}.doStop({{$value.id}})">禁用</a>' +
			'                       {{/if}}'+		
			'						<a onclick="{{$page}}.doDelete({{$value.id}})">删除</a>' +
			'					</div>' +
			'				</div>' +
			'			</div>' +
			'			{{/each}}' +
			'			{{if adminList.length==0}}' +
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
	loaded: function () {
		this.doSearch();

	}
}, {
	doSearch: function () {		
		var that = this;
		$wk.api.adminSystem.adminGetList(that.templateData.search,function (result) {
			that.templateData.adminList = result.data.dataList;
			that.templateData.PAGE = $wk.pagination.eachHtml('admin-list',
				(that.templateData.search.pageNumber + 1),
				that.templateData.search.pageSize,
				result.data.dataCount,
				5,
				function (pageIndex) {
					that.templateData.search.pageNumber = pageIndex - 1;
					that.doSearch();
				}
			);
			that.drawView(that.parseTemplate(that.template, that.templateData));
		});

	},
	/**
	 * 执行编辑
	 * @param id
	 */
	doEdit: function (id) {
		$wk.page.load('admin-save', [id]);
	},
	/**
	 * 执行删除
	 * @param id
	 */
	doDelete: function (id) {
		var that = this;
		$wk.msg.showConfirm('确定要删除管理员？', function () {
			$wk.api.adminSystem.adminDelete(id, function () {
				that.pageLoaded();
			});
		});
	},
	/**
	 * 启用
	 */
	doStart: function (id) {		
		var that = this;
		$wk.msg.showConfirm('确定要启用管理员？', function () {
			$wk.api.adminSystem.adminStart(id, function () {
				that.pageLoaded();
			});
		});
	},
	/**
	 * 禁用
	 */
	doStop: function (id) {		
		var that = this;
		$wk.msg.showConfirm('确定要禁用管理员？', function () {
			$wk.api.adminSystem.adminStop(id, function () {
				that.pageLoaded();
			});
		});
	}
}));

