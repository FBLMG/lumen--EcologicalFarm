/**
 * 变量管理 - 保存
 */
$wk.page.createPage('variable_save', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			statusOptions: [{
				title: '正常',
				value: 1
			}, {
				title: '禁用',
				value: 2
			},
			{
				title: '草稿',
				value: 3
			}],
			typeOptions: [ {
				title: '发现',
				value: 1
			}, {
				title: '吐槽',
				value: 2
			}],
			Va: {
				title: '',
				image: '',
				url: '',
				type: 0,
				sort: 0,
				status: 1,
				create_at: '',
				AType: 1
			}
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="form ui-form2">' +
			'			<div class="box">' +

			'				<div class="vo">' +
			'					<div class="l">变量group ：</div>' +
			'					<div class="r">' +
			'						<input class="ui-input" placeholder="变量group" value="{{variableMange.group}}" id="variable_save_group" style="width: 498px;">' +
			'					</div>' +
			'				</div>' +
			'				<div class="vo">' +
			'					<div class="l">变量desc ：</div>' +
			'					<div class="r">' +
			'						<input class="ui-input" placeholder="变量desc" value="{{variableMange.desc}}" id="variable_save_desc" style="width: 498px;">' +
			'					</div>' +
			'				</div>' +
			'				<div class="vo">' +
			'					<div class="l"><span class="required">*</span>变量name ：</div>' +
			'					<div class="r">' +
				//'						<input class="ui-input" placeholder="变量name" value="{{variableMange.name}}" id="variable_save_name" style="width: 200px;">' +
			'			            <textarea class="ui-textarea" id="variable_save_name">{{variableMange.name}}</textarea>' +
			'					</div>' +
			'				</div>' +
			'				<div class="vo">' +
			'					<div class="l">变量value ：</div>' +
			'					<div class="r">' +
			//'						<input class="ui-input" placeholder="变量value" value="{{variableMange.value}}" id="variable_save_value" style="width: 200px;">' +
			'			             <textarea  class="ui-textarea" id="variable_save_value">{{variableMange.value}}</textarea>' +
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
		this.templateData.variableId = parseInt(this.params[0] || 0);
	},
	loaded: function () {
		var that = this;
		if (that.templateData.variableId) { // 编辑
			$wk.api.variableSystem.get(that.templateData.variableId, function (resultVa) {
				that.templateData.variableMange = resultVa.data;
				that.drawView(that.parseTemplate(that.template, that.templateData));
			});
		} else {
			that.templateData.variableMange = {};
			that.drawView(that.parseTemplate(that.template, that.templateData));
		}
	}
}, {
	doSave: function () {
		var that = this;
		that.templateData.variableMange.group = $('#variable_save_group').val();
		that.templateData.variableMange.name = $('#variable_save_name').val();
		that.templateData.variableMange.value = $('#variable_save_value').val();
		that.templateData.variableMange.desc = $('#variable_save_desc').val();
		if (that.templateData.variableMange.name == '') {
			$wk.msg.showAlert('请填写变量name');
			return;
		}

		if (that.templateData.variableId) { // 编辑
			$wk.api.variableSystem.edit(that.templateData.variableMange, function () {
				$wk.msg.showAlert('编辑成功', function () {
					$wk.page.load('variable_list',[1]);
				});
			});
		} else { // 添加
			that.templateData.variableMange.AType = 1;
			$wk.api.variableSystem.add(that.templateData.variableMange, function () {
				$wk.msg.showAlert('添加成功', function () {
					$wk.page.load('variable_list');
				});
			});
		}
	}
}));