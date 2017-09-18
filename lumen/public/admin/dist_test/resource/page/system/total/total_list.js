/**
 * 访问统计列表
 */
$wk.page.createPage('total_list', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			pageLabels: {
				'index': '首页',
				'freeFood-index': '不用你花钱-免单',
				'freeFood-game': '不用你花钱-摇奖',
				'activity_rule': '摇签',
				'activity_record': '摇签-中奖记录',
				'activity2_result2': '我的免单',
				'activity2_result1': '摇奖记录',
				'summer': '商品',
				'my_address': '收货地址',
				'rules-index': '免单规则',
				'shop_shopping_cart': '领取奖品',
				'my_address_save': '编辑地址',
			},
			pageOpLabels: {
				'load': '进入页面',
				'clickBanner': '点击Banner',
			}
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="page-nav ui-nav">' +
			'			<div class="left">' +
			'			</div>' +
			'			<div class="right">' +
			'				<input id="total_list_startTime" class="ui-input ui-form-datetime" type="text" placeholder="开始时间" value="{{search.startTime1}}">' +
			'				<input id="total_list_endTime" class="ui-input ui-form-datetime" type="text" placeholder="结束时间" value="{{search.endTime1}}">' +
			'				<select class="ui-select" onchange="{{$page}}.templateData.search.pname=this.value;">' +
			'					<option value="0">请选择页面</option>' +
			'                   {{each pageLabels}}' +
			'					    <option value="{{$index}}" {{if $index==search.pname}} selected="true"{{/if}}>{{$value}}</option>' +
			'                   {{/each}}' +
			'				</select>' +
			'				<select class="ui-select" onchange="{{$page}}.templateData.search.ptitle=this.value;">' +
			'					<option value="0">请选择页面操作</option>' +
			'                   {{each pageOpLabels}}' +
			'					    <option value="{{$index}}" {{if $index==search.ptitle}} selected="true"{{/if}}>{{$value}}</option>' +
			'                   {{/each}}' +
			'				</select>' +
			'				<a class="ui-btn" onclick="{{$page}}.doSearch(event, this);">查询</a>' +
			'				<a class="ui-btn" onclick="{{$page}}.doExplode();" target="_blank">导出</a>' +
			'			</div>' +
			'		</div>' +
			'		{{if totalRecordItems}}' +
			'		<div class="page-table ui-table ui-table-hover">' +
			'			<div class="ui-th">' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">页面</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">页面操作</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:130px;">' +
			'					<div class="ui-con">创建时间</div>' +
			'				</div>' +
			'			</div>' +
			'			{{each totalRecordItems}}' +
			'			<div class="ui-tr">' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">{{$value.pname}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">{{$value.ptitle}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:130px;">' +
			'					<div class="ui-con">{{$value.createAt | datetime}}</div>' +
			'				</div>' +
			'			</div>' +
			'			{{/each}}' +
			'			{{if totalRecordItems.length==0}}' +
			'			<div class="ui-tr ui-tr-nohover">' +
			'				<div class="page-empty">没有任何数据</div>' +
			'			</div>' +
			'			{{/if}}' +
			'		</div>' +
			'		<div class="page-btns">' +
			'			<div class="left" style="font-size:12px;line-height:27px;">总计：{{totalRecordItemsCount}}条数据</div>' +
			'			<div class="right">{{#pageHtml}}</div>' +
			'		</div>' +
			'		{{else}}' +
			'		<div class="page-loading">正在加载中...</div>' +
			'		{{/if}}' +
			'	</div>' +
			'</div>' +
			'';
	},
	load: function () {
		this.templateData.search = {
			userId: parseInt(this.params[0] || 0),
			pname: this.params[1] || '',
			ptitle: this.params[2] || '',
			startTime1: null,
			endTime1: null,
			startTime: 0,
			endTime: 0,
			pageSize: 10,
			pageNumber: parseInt(this.params[3] || 0)
		}
	},
	loaded: function () {
		this.doSearch();
	}
}, {
	/**
	 * 执行查询
	 */
	doSearch: function () {
		var that = this;
		var startTime = $('#total_list_startTime').val();
		var endTime = $('#total_list_endTime').val();
		if (startTime) {
			that.templateData.search.startTime1 = startTime;
			that.templateData.search.startTime = $wk.date.unixtime(startTime);
		}
		if (endTime) {
			that.templateData.search.endTime1 = endTime;
			that.templateData.search.endTime = $wk.date.unixtime(endTime);
		}
		that.templateData.totalRecordItems = null;
		$wk.api.total.totalRecordGetList(that.templateData.search, function (result2) {
			that.templateData.totalRecordItems = result2.data.dataList;
			that.templateData.totalRecordItemsCount = result2.data.dataCount;
			that.templateData.pageHtml = $wk.pagination.eachHtml('total_list',
				that.templateData.search.pageNumber + 1,
				that.templateData.search.pageSize,
				result2.data.dataCount,
				5,
				function (pageIndex) {
					that.templateData.search.pageNumber = pageIndex - 1;
					that.doSearch();
				}
			);
			that.drawView(that.parseTemplate(that.template, that.templateData));
		});
	},
	doExplode: function () {
		window.location.href = $wk.api.api('activityUseRecordExecl') + "?pname=" + this.templateData.search.pname + "&ptitle=" + this.templateData.search.ptitle + "&startTime=" + $wk.date.unixtime($('#total_list_startTime').val() || 0) + "&endTime=" + $wk.date.unixtime($('#total_list_endTime').val() || 0) + "&adminId=" + $wk.api.getUserId() + "&adminToken=" + $wk.api.getUserToken();
	}
}));

