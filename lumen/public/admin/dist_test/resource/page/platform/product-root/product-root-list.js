/**
 * 产品溯源列表
 */
$wk.page.createPage('product-root-list', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			search: {
				regionId: 0,
				countyId: 0,
				name: '',
				userId: 0,
				farmId: 0,
				productName: '',
				productCategory: 0,
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
			'               <div class="item">'+
			'					<label>地区：</label>' +
			'					<select style="width:70px" id="product-root-list-region-select" class="ui-select" onchange="{{$page}}.getSubRegion(this.value);">' +
			'						<option value="0"></option>' +
			'                   	{{each regionList}}' +
			'					    	<option value="{{$value.id}}">{{$value.name}}</option>' +
			'                   	{{/each}}' +
			'					</select>' +
			'               </div>'+
			'               <div class="item">'+
			'					<label>县级：</label>' +
			'					<select style="width:70px" id="product-root-list-county-select" class="ui-select">' +
			'					</select>' +
			'               </div>'+			
			'               <div class="item">'+
			'					<label>档案名称 ：</label>' +		
			'					<input style="width:90px;" type="text" class="ui-input" onchange="{{$page}}.templateData.search.name=this.value;" value="{{search.name}}" />' +
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
			'		{{if productRootList}}' +
			'		<div class="page-table ui-table ui-table-hover">' +
			'			<div class="ui-th">' +
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con">农场主名称</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con">农场名称</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con">档案名称</div>' +
			'				</div>' +				
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">地区</div>' +
			'				</div>' +				
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">县级</div>' +
			'				</div>' +					
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con">企业信息</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con">创建时间</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con">操作</div>' +
			'				</div>' +
			'			</div>' +
			'			{{each productRootList}}' +
			'			<div class="ui-tr">' +
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.userName}}">{{$value.userName}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.farmName}}">{{$value.farmName}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.fileName}}">{{$value.fileName}}</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">{{$value.reginName}}</div>' +
			'				</div>' +			
			'				<div class="ui-td" style="width:60px;">' +
			'					<div class="ui-con">{{$value.countyName}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:120px;">' +
			'					<div class="ui-con ui-ellipsis" title="{{$value.enterpriseInformation}}">{{$value.enterpriseInformation}}</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:200px;">' +
			'					<div class="ui-con" title="{{$value.createAt | datetime}}">'+
			'                   	{{if $value.createAt}}'+
			'						<p style="margin:0;">{{$value.createAt | datetime}}</p>'+
			'                   	{{/if}}'+
			'					</div>' +
			'				</div>' +
			'				<div class="ui-td" style="width:90px;">' +
			'					<div class="ui-con ui-btns">' +
			'						<a onclick="{{$page}}.doEdit({{$value.id}})">查看</a> -' +
			'					</div>' +
			'				</div>' +
			'			</div>' +
			'			{{/each}}' +
			'			{{if productRootList.length==0}}' +
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
		that.templateData.productRootList = null;
		that.templateData.search.startAt = $wk.date.unixtime(that.templateData.search.startAt);
		that.templateData.search.endAt = $wk.date.unixtime(that.templateData.search.endAt + ' 23:59:59');
		that.templateData.search.regionId = parseInt($("#product-root-list-region-select").val() || 0);
		that.templateData.search.countyId = parseInt($("#product-root-list-county-select").val() || 0);
		$wk.api.productRoot.productTraceabilityGetList(that.templateData.search, function (result) {
			that.templateData.productRootList = result.data.dataList;
			that.templateData.pageHtml = $wk.pagination.eachHtml('product-root-list',
				that.templateData.search.pageNumber + 1,
				that.templateData.search.pageSize,
				result.data.dataCount,
				5,
				function (pageIndex) {
					that.templateData.search.pageNumber = pageIndex - 1;
					that.drawSearch();
				}
			);
			that.templateData.search.startAt = $wk.date.date(that.templateData.search.startAt);
			that.templateData.search.endAt = $wk.date.date(that.templateData.search.endAt);
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
		$wk.page.load('product-root-save', [id]);
	},
	/**
	 * 获取县级
	 */
	getSubRegion: function (regionId) {
		var that = this;
		var search = {
			regionId: regionId
		}
		$wk.api.area.subRegionGet(search,function (result) {
			that.templateData.countyList = result.data.dataList;
			$("#product-root-list-county-select").html('<option value="0"></option>');
			for(var i in that.templateData.countyList){
				var item = that.templateData.countyList[i];
				$("#product-root-list-county-select").append('<option value="'+item.id+'">'+item.name+'</option>');
			}
		});
	}
}));

