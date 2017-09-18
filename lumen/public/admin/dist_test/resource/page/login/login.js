/**
 * 登录 page
 */
$wk.page.createPage(
	'login',
	new $wk.page.newPage(
		{
			init: function () {
				this.templateData = {};
				this.template = '' +
					'<div class="box">' +
					'	<div class="header">' +
					'		<div class="logo"><h1>徐家庄</h1></div>' +
					'		<div class="desc">登录</div>' +
					'	</div>' +
					'	<div class="container">' +
					'		<form class="login-box" onsubmit="return {{$page}}.doLogin();">' +
					'			<div class="item">' +
					'				<div class="left" >' +
					'					用户名：' +
					'				</div>' +
					'				<div class="right" >' +
					'					<input class="ui-input username" required placeholder="请输入用户名">' +
					'				</div>' +
					'			</div>' +
					'			<div class="item">' +
					'				<div class="left">' +
					'					登录密码：' +
					'				</div>' +
					'				<div class="right">' +
					'					<input type="password" class="ui-input pwd" required placeholder="请输入登录密码">' +
					'				</div>' +
					'			</div>' +
					'			<div class="item">' +
					'				<div class="right">' +
					'					<button type="submit" class="ui-btn" >登录</button>' +
					'				</div>' +
					'			</div>' +
					'		</form>' +
					'	</div>' +
					'	<div class="footer">&copy;Copyright 2017 徐家庄</div>' +
					'</div>' +
					'';
			},
			load: function () {
				$layout.header.doHideAllItem();
				$layout.header.doHideMenu();
				$('.layout-container > .layout-sidebar-left').hide();
				$layout.footer.hide();
			},
			loaded: function () {
				this.drawView(this.parseTemplate(this.template, this.templateData));
				var username = $wk.cookie.get('__USERNAME__');
				var pwd = $wk.cookie.get('__PASSWORD__');
				$('#login .login-box .username').val(username);
				$('#login .login-box .pwd').val(pwd);

			},
			unload: function () {
				$layout.header.doShowAllItem();
				$layout.header.doShowMenu();
				$layout.footer.show();
				$('.layout-container > .layout-sidebar-left').show();
			}
		},
		{
			rememberPwd: function() {
				var tag = $('#login .login-box .ui-cbox').is(':checked');
				if(tag){
					var username = $('#login .login-box .username')[0].value;
					var pwd = $('#login .login-box .pwd')[0].value;
					$wk.cookie.set('__USERNAME__', username, 24*7);
					$wk.cookie.set('__PASSWORD__', pwd, 24*7);
				}else{
					$wk.cookie.remove('__USERNAME__');
					$wk.cookie.remove('__PASSWORD__');
				}
			},


			/**
			 * 登录按钮的点击事件
			 * @returns {boolean}
			 */
			doLogin: function(){
				var that = this;
				that.rememberPwd();
				var username = $('#login .login-box .username')[0].value;
				var pwd = $('#login .login-box .pwd')[0].value;


				$wk.api.login(username, pwd,{
					success: function(res){
						//保存登录信息
						$layout.header.currentType = 0;
						$wk.api.saveUserInfo(res.data.id, res.data.token, res.data.power);
						$wk.page.load('home');
					}
				});
				return false;
			}
		}
	)
);

