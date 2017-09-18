/**
 * 上级地区保存
 */
$wk.page.createPage('high-area-save', new $wk.page.newPage({
	init: function () {
		this.templateData = {};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="form ui-form2">' +
			'			<div class="box">' +
			'				<div class="vo">' +
			'					<div class="l">地区名称：</div>' +
			'					<div class="r">' +
			'						<input id="high-area-save-name" class="ui-input" type="text" value="{{region.name}}" style="width: 422px;">' +
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
	},
	loaded: function () {
		var that = this;
		if (that.templateData.regionId) {
			$wk.api.showLoading();
			$wk.api.area.regionGet(that.templateData.regionId, function (result) {
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
		that.templateData.region.name = $('#high-area-save-name').val();
		if (that.templateData.regionId) { // 编辑
			$wk.api.area.parentRegionUpdate(that.templateData.region, function () {
				$wk.msg.showAlert('编辑成功', function () {
					$wk.page.load('high-area-list');
				});
			});
		} else { // 添加
			$wk.api.area.parentRegionInsert(that.templateData.region, function () {
				$wk.msg.showAlert('添加成功', function () {
					$wk.page.load('high-area-list');
				});
			});
		}
	}
}));