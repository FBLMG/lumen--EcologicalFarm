/**
 * 新闻列表
 */
$wk.page.createPage('news-list', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			search: {
				title: '',
				startAt: 0,
				endAt: 0,
				pageSize: 10,
				pageNumber: parseInt(this.params[1] || 0)
			}
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="page-nav ui-nav">' +
			'			<div class="left">' +
			'               <a class="ui-btn ui-btn-success" href="#/news-save">添加新闻</a>' +
			'			</div>' +
			'			<div class="left">' +
			'               <div class="item">'+
			'					<label>新闻标题 ：</label>' +		
			'					<input style="width:90px;" type="text" class="ui-input" onchange="{{$page}}.templateData.search.title=this.value;" value="{{search.title}}" />' +
			'               </div>'+
			'               <div class="item">'+
			'					<label>创建时间：</label>' +
			'					<input type="text" class="ui-input page-input-date ui-form-datetime" onchange="{{$page}}.templateData.search.startAt=this.value;" value="{{search.startAt}}" />' +
			'					<span>至</span>' +
			'					<input type="text" class="ui-input page-input-date ui-form-datetime" onchange="{{$page}}.templateData.search.endAt=this.value;" value="{{search.endAt}}" />' +
			'               </div>'+
			'			</div>' +			
			'			<div class="right">' +
			'				<a class="ui-btn" onclick="{{$page}}.doSearch();">查询</a>' +
			'			</div>' +
			'		</div>' +
			'		{{if newsList}}' +
			'		<div class="page-table ui-table ui-table-hover">' +
			'			<div class="ui-th">' +
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con">新闻标题</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con">新闻内容</div>' +
			'				</div>' +				
			'				<div class="ui-td" style="width:70px;">' +
			'					<div class="ui-con">图片</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">视频地址</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:150px;">' +
			'					<div class="ui-con">创建时间</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:150px;">' +
			'					<div class="ui-con">更新时间</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con">操作</div>' +
			'				</div>' +
			'			</div>' +
			'			{{each newsList}}' +
			'			<div class="ui-tr">' +			
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.title}}">{{$value.title}}</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.content}}">{{$value.content}}</div>' +
			'				</div>' +						
			'				<div class="ui-td" style="width:70px;">' +
			'					<div class="ui-con" title="{{$value.images}}"><img src="' + CONFIG_DOMAIN_UPLOAD + '{{$value.images | image:[56, 56]}}"></div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.video}}"><a href="{{$value.video}}" target="_blank">{{$value.video}}</a></div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:150px;">' +
			'					<div class="ui-con" title="{{$value.createAt | datetime}}">'+
			'                   	{{if $value.createAt}}'+
			'						<p style="margin:0;">{{$value.createAt | datetime}}</p>'+
			'                   	{{/if}}'+
			'					</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:150px;">' +
			'					<div class="ui-con" title="{{$value.updateAt | datetime}}">'+
			'                   	{{if $value.updateAt}}'+
			'						<p style="margin:0;">{{$value.updateAt | datetime}}</p>'+
			'                   	{{/if}}'+
			'					</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con ui-btns">' +
			'						<a onclick="{{$page}}.doEdit({{$value.id}})">编辑</a> -' +
			'						<a onclick="{{$page}}.doDelete({{$value.id}})">删除</a>' +
			'					</div>' +
			'				</div>' +
			'			</div>' +
			'			{{/each}}' +
			'			{{if newsList.length==0}}' +
			'			<div class="ui-tr ui-tr-nohover">' +
			'				<div class="page-empty">没有任何数据</div>' +
			'			</div>' +
			'			{{/if}}' +
			'		</div>' +
			'		<div class="page-btns">' +
			'			<div class="left">' +
			'			</div>' +
			'			<div class="right">{{#pageHtml}}</div>' +
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
	/**
	 * 执行查询
	 */
	doSearch: function () {
		var that = this;
		that.templateData.newsList = null;
		that.templateData.search.startAt = $wk.date.unixtime(that.templateData.search.startAt);
		that.templateData.search.endAt = $wk.date.unixtime(that.templateData.search.endAt + ' 23:59:59');
		$wk.api.news.newsGetList(that.templateData.search, function (result) {
			that.templateData.newsList = result.data.dataList;
			that.templateData.pageHtml = $wk.pagination.eachHtml('news-list',
				that.templateData.search.pageNumber + 1,
				that.templateData.search.pageSize,
				result.data.dataCount,
				5,
				function (pageIndex) {
					that.templateData.search.pageNumber = pageIndex - 1;
					that.doSearch();
				}
			);
			that.templateData.search.startAt = $wk.date.date(that.templateData.search.startAt);
			that.templateData.search.endAt = $wk.date.date(that.templateData.search.endAt);
			that.drawView(that.parseTemplate(that.template, that.templateData));
		});
	},
	/**
	 * 执行编辑
	 * @param id
	 */
	doEdit: function (id) {
		$wk.page.load('news-save', [id]);
	},
	/**
	 * 执行删除
	 * @param id
	 */
	doDelete: function (id) {
		var that = this;
		$wk.msg.showConfirm('此操作为真实删除数据，你确定要执行吗？', function () {
			$wk.api.news.newsDelete(id, function () {
				that.pageLoaded();
			});
		});
	}
}));

