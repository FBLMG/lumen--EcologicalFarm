/**
 * 管理员管理 - 保存
 */
$wk.page.createPage('admin-save', new $wk.page.newPage({
	init: function () {
		this.templateData = {
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="form ui-form2">' +
			'			<div class="box">' +
			'				<div class="vo">' +
			'					<div class="l">用户名 ：</div>' +
			'					<div class="r">' +
			'						<input class="ui-input" placeholder="用户名" value="{{adminMange.username}}" id="admin-save-username" style="width: 498px;">' +
			'					</div>' +
			'				</div>' +
			'				<div class="vo">' +
			'					<div class="l">密码 ：</div>' +
			'					<div class="r">' +
			'						<input class="ui-input" placeholder="密码" value="" id="admin-save-password" style="width: 498px;">' +
			'						<span class="help">创建用户时，必须填写<br>编辑用户时可不填写，不填写则表示不修改密码</span>' +
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
		this.templateData.adminId = parseInt(this.params[0] || 0);
	},
	loaded: function () {
		var that = this;
		if (that.templateData.adminId) { // 编辑
			$wk.api.adminSystem.adminGet(that.templateData.adminId, function (resultAdmin) {
				that.templateData.adminMange = resultAdmin.data;
				that.drawView(that.parseTemplate(that.template, that.templateData));
			});
		} else {
			that.templateData.adminMange = {};
			that.drawView(that.parseTemplate(that.template, that.templateData));
		}
	}
}, {
	doSave: function () {
		var that = this;
		that.templateData.adminMange.username = $('#admin-save-username').val();
		that.templateData.adminMange.password = $('#admin-save-password').val();
		if (that.templateData.adminMange.username == '') {
			$wk.msg.showAlert('请填写用户名');
			return;
		}
		if (that.templateData.adminId) { // 编辑
			$wk.api.adminSystem.adminUpdate(that.templateData.adminMange, function () {
				$wk.msg.showAlert('编辑成功', function () {
					$wk.page.load('admin-list');
				});
			});
		} else { // 添加
			if (that.templateData.adminMange.password == '') {
				$wk.msg.showAlert('请填写密码');
				return;
			}
			$wk.api.adminSystem.adminInsert(that.templateData.adminMange, function () {
				$wk.msg.showAlert('添加成功', function () {
					$wk.page.load('admin-list');
				});
			});
		}
	}
}));