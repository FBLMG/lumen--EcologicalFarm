/**
 * 农场保存
 */
$wk.page.createPage('farm-save', new $wk.page.newPage({
	init: function () {
		this.templateData = {
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
			'		<div class="form ui-form2">' +
			'			<div class="box">' +
			'				<div class="vo">' +
			'					<div class="l">农场名称：</div>' +
			'					<div class="r">' +
			'						<input id="farm-save-name" class="ui-input" type="text" placeholder="标题" value="{{farm.title}}" style="width: 422px;">' +
			'					</div>' +
			'				</div>' +
			'				<div class="vo">' +
			'					<div class="l">地区：</div>' +
			'					<div class="r">' +
			'						<select id="farm-save-region-select" style="width:70px" class="ui-select" onchange="{{$page}}.getSubRegion(this.value);">' +
			'                   			{{each regionList}}' +
			'					    			<option value="{{$value.id}}" {{if $value.id===currentRegionId}} selected="true" {{/if}}>{{$value.name}}</option>' +
			'                   			{{/each}}' +
			'						</select>' +
			'					</div>' +
			'				</div>' +	

			'				<div class="vo">' +
			'					<div class="l">县级：</div>' +
			'					<div class="r">' +
			'						<select id="farm-save-county-select" style="width:70px" class="ui-select">' +
			'                           {{each countyList}}'+
			'                       		<option value="{{$value.id}}" {{if $value.id===farm.countyId}} selected="true" {{/if}}>{{$value.name}}</option>'+
			'                           {{/each}}'+
			'						</select>' +
			'					</div>' +
			'				</div>' +		
			'				<div class="vo">' +
			'					<div class="l">视频：</div>' +
			'					<div class="r">' +
			'						<input id="farm-save-video" class="ui-input" type="text" placeholder="视频" value="{{farm.video}}" style="width: 422px;">' +
			'                       <span class="help">第三方视频地址(http/https 开头)</span>'+
			'					</div>' +
			'				</div>' +				
			'				<div class="vo">' +
			'					<div class="l">图片：</div>' +
			'					<div class="r">' +
			'						<div class="upload_image">{{#farmSaveImg}}</div>' +
			'					</div>' +
			'				</div>' +	
			'				<div class="vo">' +
			'					<div class="l">内容：</div>' +
			'					<div class="r">' +
			'						<textarea id="farm-save-content" class="page-textarea" style="width: 80%;">{{farm.content}}</textarea>' +
			'					</div>' +
			'				</div>' +
			'			</div>' +
			'			<div class="btn">' +
			'				<a class="ui-btn ui-btn-blue" onclick="{{$page}}.doSave();">保存</a>' +
			'			</div>' +
			'			</div>' +
			'		</div>' +
			'	</div>' +
			'</div>' +
			'';
	},
	load: function () {
		this.templateData.farm = {};
		this.templateData.farmId = parseInt(this.params[0] || '');
		this.templateData.currentRegionId = this.templateData.farmId || '';
	},
	loaded: function () {
		var that = this;
		$wk.api.showLoading();
		if (that.templateData.farmId) {
			$wk.api.farm.farmGet(that.templateData.farmId, function (result) {
				that.templateData.farm = result.data;
				that.templateData.currentRegionId = result.data.regionId;
				that.getRegion(that.templateData.currentRegionId);
			});
		} else {
			that.getRegion();
		}

	},
	draw: function () {
		UM.clearCache('farm-save-content');
		var that = this;
		var um = UM.getEditor('farm-save-content');
		um.addListener('blur', function () {
			that.templateData.farm.content = um.getContent();
		});

	}
}, {
	doSave: function () {
		var that = this;
		that.templateData.farm.name = $('#farm-save-name').val();
		console.log(that.templateData.farm.name)
		that.templateData.farm.regionId = $('#farm-save-region-select').val();
		that.templateData.farm.countyId = $('#farm-save-county-select').val();
		that.templateData.farm.video = $('#farm-save-video').val();
		console.log(that.templateData.farm)
		if (that.templateData.farmId) { // 编辑
			$wk.api.farm.farmUpdate(that.templateData.farm, function () {
				$wk.msg.showAlert('编辑成功', function () {
					$wk.page.load('farm-list');
				});
			});
		} else { // 添加
			$wk.api.farm.farmInsert(that.templateData.farm, function () {
				$wk.msg.showAlert('添加成功', function () {
					$wk.page.load('farm-list');
				});
			});
		}
	},
	//获取地区
	getRegion: function (regionId) {
		var that = this;
		var regionId = regionId;
		$wk.api.area.regionGetList(that.templateData.areaSearch,function (result) {
			that.templateData.regionList = result.data.dataList;
			if(!regionId) {
				regionId= result.data.dataList[0].id;
			}
			that.getSubRegion(regionId);
			
		});
	},
	//获取县级
	getSubRegion: function (regionId) {
		var that = this;
		console.log(regionId)

		var get = {regionId:regionId};
		$wk.api.area.subRegionGet(get,function (result) {
			var countyList = result.data.dataList;
			console.log('获取县级后',that.templateData)
			that.templateData.countyList = result.data.dataList;
			that.templateData.currentRegionId = result.data.dataList[0].regionId;
			that.loadEdit();
		});
	},
	loadEdit: function () {
		var that = this;			
		that.templateData.farmSaveImg = $wk.upload_image.echoHtml('farmSaveImg',
			that.templateData.farm.images ? [that.templateData.farm.images] : [], false, 0,
			function (imgFile) {
				that.templateData.farm.images = imgFile;
			},
			function () {
				that.templateData.farm.images = null;
			}
		);

		that.drawView(that.parseTemplate(that.template, that.templateData));
		$wk.api.hideLoading();
	}
}));