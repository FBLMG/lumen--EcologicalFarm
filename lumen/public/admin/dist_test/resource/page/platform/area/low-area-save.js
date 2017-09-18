/**
 * 下级地区保存
 */
$wk.page.createPage('low-area-save', new $wk.page.newPage({
	init: function () {
		this.templateData = {};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="form ui-form2">' +
			'			<div class="box">' +
			'				<div class="vo">' +
			'					<div class="l">县级名称：</div>' +
			'					<div class="r">' +
			'						<input id="low-area-save-name" class="ui-input" type="text" value="{{region.name}}" style="width: 422px;">' +
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
		this.templateData.region = {};
		this.templateData.regionId = parseInt(this.params[0] || 0);
		this.templateData.countyId = parseInt(this.params[1] || 0);
	},
	loaded: function () {
		var that = this;
		if (that.templateData.countyId) {
			$wk.api.showLoading();
			$wk.api.area.regionGet(that.templateData.countyId, function (result) {
				that.templateData.region = result.data;
				loadEdit();
			});
		} else {
			loadEdit();
		}
		function loadEdit() {
			that.drawView(that.parseTemplate(that.template, that.templateData));
			$wk.api.hideLoading();
		}
	}
}, {
	doSave: function () {
		var that = this;
		that.templateData.region.name = $('#low-area-save-name').val();
		if (that.templateData.countyId) { // 编辑
			$wk.api.area.subRegionUpdate(that.templateData.region, function () {
				$wk.msg.showAlert('编辑成功', function () {
					$wk.page.load('low-area-list',[that.templateData.region.regionId]);
				});
			});
		} else { // 添加
			that.templateData.region.regionId = that.templateData.regionId;
			$wk.api.area.subRegionInsert(that.templateData.region, function () {
				$wk.msg.showAlert('添加成功', function () {
					$wk.page.load('low-area-list',[that.templateData.region.regionId]);
				});
			});
		}
	}
}));