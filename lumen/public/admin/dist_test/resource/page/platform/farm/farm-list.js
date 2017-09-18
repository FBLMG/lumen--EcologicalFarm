/**
 * 农场列表
 */
$wk.page.createPage('farm-list', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			search: {
				name: '',
				regionId: 0,
				countyId: 0,
				startAt: 0,
				endAt: 0,
				pageSize: 10,
				pageNumber: parseInt(this.params[1] || 0)
			},
			areaSearch: {
				name: '',
				startAt: 0,
				endAt: 0,
				pageSize: 0,
				pageNumber: 0
			}
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="page-nav ui-nav">' +
			'			<div class="left">' +
			'               <a class="ui-btn ui-btn-success" href="#/farm-save">添加农场</a>' +
			'			</div>' +
			'			<div class="left">' +
			'               <div class="item">'+
			'					<label>农场名称 ：</label>' +		
			'					<input style="width:90px;" type="text" class="ui-input" onchange="{{$page}}.templateData.search.name=this.value;" value="{{search.name}}" />' +
			'               </div>'+
			'               <div class="item">'+
			'					<label>地区：</label>' +
			'					<select style="width:70px" id="farm-list-region-select" class="ui-select" onchange="{{$page}}.getSubRegion(this.value);">' +
			'						<option value="0"></option>' +
			'                   	{{each regionList}}' +
			'					    	<option value="{{$value.id}}">{{$value.name}}</option>' +
			'                   	{{/each}}' +
			'					</select>' +
			'               </div>'+
			'               <div class="item">'+
			'					<label>县级：</label>' +
			'					<select style="width:70px" id="farm-list-county-select" class="ui-select">' +
			'					</select>' +
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
			'		{{if farmList}}' +
			'		<div class="page-table ui-table ui-table-hover">' +
			'			<div class="ui-th">' +
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con">农场主ID</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con">农场名称</div>' +
			'				</div>' +				
			'				<div class="ui-td" style="width:70px;">' +
			'					<div class="ui-con">图片</div>' +
			'				</div>' +		
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con">内容</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:100px;">' +
			'					<div class="ui-con">视频</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">地区</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">县级</div>' +
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
			'			{{each farmList}}' +
			'			<div class="ui-tr ui-tr-status{{$value.status}}">' +			
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con">{{$value.userId}}</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.title}}">{{$value.title}}</div>' +
			'				</div>' +						
			'				<div class="ui-td" style="width:70px;">' +
			'					<div class="ui-con" title="{{$value.images}}"><img src="' + CONFIG_DOMAIN_UPLOAD + '{{$value.images | image:[56, 56]}}"></div>' +
			'				</div>' +	
			'				<div class="ui-td" style="width:160px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.content}}">{{$value.content}}</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:100px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.video}}"><a href="{{$value.video}}" target="_blank">{{$value.video}}</a></div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">{{$value.region}}</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">{{$value.county}}</div>' +
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
			'			{{if farmList.length==0}}' +
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
		that.templateData.farmList = null;
		that.templateData.search.startAt = $wk.date.unixtime(that.templateData.search.startAt);
		that.templateData.search.endAt = $wk.date.unixtime(that.templateData.search.endAt + ' 23:59:59');
		that.templateData.search.regionId = parseInt($("#farm-list-region-select").val() || 0);
		that.templateData.search.countyId = parseInt($("#farm-list-county-select").val() || 0);
		$wk.api.farm.farmGetList(that.templateData.search, function (result) {
			that.templateData.farmList = result.data.dataList;
			that.templateData.pageHtml = $wk.pagination.eachHtml('farm-list',
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
			console.log(that.templateData.search)
			$wk.api.area.regionGetList(that.templateData.areaSearch,function (result) {
				that.templateData.regionList = result.data.dataList;
				console.log(that.templateData.regionList)
				that.drawView(that.parseTemplate(that.template, that.templateData));
			});
		});
	},
	/**
	 * 执行编辑
	 * @param id
	 */
	doEdit: function (id) {
		$wk.page.load('farm-save', [id]);
	},
	/**
	 * 执行删除
	 * @param id
	 */
	doDelete: function (id) {
		var that = this;
		$wk.msg.showConfirm('此操作为真实删除数据，你确定要执行吗？', function () {
			$wk.api.farm.farmDelete(id, function () {
				that.pageLoaded();
			});
		});
	},
	/**
	 * 获取县级
	 */
	getSubRegion: function (regionId) {
		var that = this;
		var get = {regionId:regionId};
		$wk.api.area.subRegionGet(get,function (result) {
			that.templateData.countyList = result.data.dataList;
			$("#farm-list-county-select").html('<option value="0"></option>');
			for(var i in that.templateData.countyList){
				var item = that.templateData.countyList[i];
				$("#farm-list-county-select").append('<option value="'+item.id+'">'+item.name+'</option>');
			}
		});
	}
}));

