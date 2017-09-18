function createApp() {

	//////////////////////////////////////////////
	// 首页
	var platformHomeObj = {
		type: 1
	};

	//////////////////////////////////////////////
	// 农场
	var platformFarmObj = {
		type: 1,
		level1: 'menu-farm',
		level2: 'sub-menu-farm'
	};

	//////////////////////////////////////////////
	// 新闻
	var platformNewsObj = {
		type: 1,
		level1: 'menu-news',
		level2: 'sub-menu-news'
	};

	//////////////////////////////////////////////
	// 产品溯源
	var platformProductRootObj = {
		type: 1,
		level1: 'menu-product-root',
		level2: 'sub-menu-product-root'
	};

	//////////////////////////////////////////////
	// 地区
	var platformAreaObj = {
		type: 1,
		level1: 'menu-area',
		level2: 'sub-menu-area'
	}
	//////////////////////////////////////////////
	//用户
	var platformUsersObj = {
		type: 1,
		level1: 'menu-users',
		level2: 'sub-menu-users'
	};

	//////////////////////////////////////////////
	//系统
	var platformCacheObj = {
		type: 1,
		level1: 'menu-system',
		level2: 'sub-menu-cache'
	};
	var platformVariableObj = {
		type: 1,
		level1: 'menu-system',
		level2: 'sub-menu-variable'
	};
	var platformAdminObj = {
		type: 1,
		level1: 'menu-system',
		level2: 'sub-menu-admin'
	};
	var platformTotalObj = {
		type: 1,
		level1: 'menu-system',
		level2: 'sub-menu-total'
	};

	var pageList = {

		//////////////////////////////////////////////
		// 首页
		home: platformHomeObj,

		//////////////////////////////////////////////
		// 农场
		"farm-list": platformFarmObj,
		"farm-save": platformFarmObj,

		//////////////////////////////////////////////
		// 新闻
		"news-list": platformNewsObj,
		"news-save": platformNewsObj,

		//////////////////////////////////////////////
		// 产品溯源
		"product-root-list": platformProductRootObj,
		"product-root-save": platformProductRootObj,


		//////////////////////////////////////////////
		// 地区
		"high-area-list": platformAreaObj,
		"high-area-save": platformAreaObj,
		"low-area-list": platformAreaObj,
		"low-area-save": platformAreaObj,

		
		//////////////////////////////////////////////
		// 用户
		'users-list': platformUsersObj,
		'users-msg': platformUsersObj,

		//////////////////////////////////////////////
		// 系统
		clean_cache: platformCacheObj,

		variable_list: platformVariableObj,
		variable_save: platformVariableObj,

		"admin-list": platformAdminObj,
		"admin-save": platformAdminObj,

		total_list: platformTotalObj
	};
	var pageIndex = function (pageName) {
		if (pageList[pageName]) {
			return pageList[pageName];
		}
		return 0;
	};

	$wk.page.createPage('home', new $wk.page.newPage({
		init: function () {
			this.drawView('欢迎进入后台管理平台，请选择左边菜单的对应功能');
		},
		load: function () {
		}
	}));

	$wk.page.createApp('login', [], {
		elementRoot: document.getElementById('layout-app'),
		pageLoad: function (page) {
			$('#layout-header > .header > .link').find('.l' + pageIndex(page.id).type).addClass('on');

			$layout.header.doInitSidebar(pageIndex(page.id).type, page.id, 0, false);

			if (pageIndex(page.id).level1) {
				$('.layout-container > .layout-sidebar-left > .item').find('.' + pageIndex(page.id).level1).show();
				if (pageIndex(page.id).level2) {
					$('.layout-container > .layout-sidebar-left > .item').find('.' + pageIndex(page.id).level2).show();
				}
			}
			var tagName = page.id;
			if ($('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName)[0]) {
				$('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName).addClass('on');
			} else {
				$('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName + '_' + page.params.toString().replace(',', '_')).addClass('on');
			}
			if (self != top) { // 使用iframe打开时，隐藏掉不需要的地方
				$('#layout-header').hide();
				$('#layout-sidebar-left').hide();
				$('#layout-app').css('marginLeft', '20px');
			}
		},
		pageDraw: function () {
			$('.ui-form-datetime').each(function () {
				if (parseInt($(this).data('pikaday')) === 1) {
					return;
				}
				new Pikaday({
					field: this,
					minDate: new Date('1970-01-01'),
					maxDate: new Date('2038-01-01'),
					yearRange: [1970, 2038],
					showTime: parseInt($(this).data('time')) === 1,
					use24hour: true,
					i18n: {
						previousMonth: '上一个月',
						nextMonth: '下一个月',
						months: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'],
						weekdays: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
						weekdaysShort: ['日', '一', '二', '三', '四', '五', '六'],
						midnight: '午夜',
						noon: '中午'
					}
				});
				$(this).data('pikaday', 1);
			});
			$('.copy-content').click(function () {
				$wk.msg.showAlert('' +
					'<div style="text-align: left;">' +
					'	<p style="font-size: 18px;font-weight: bold;">内部链接</p>' +
					'	<p>' + $(this).data('copy-simple') + '</p>' +
					'	<p style="font-size: 18px;font-weight: bold;">标准链接</p>' +
					'	<p>' + $(this).data('copy') + '</p>' +
					'	<p style="font-size: 18px;font-weight: bold;">说明</p>' +
					'	<em style="font-size: 12px;">内部链接只适用于悟空云商内部模块，比如广告模块中的 “链接地址” 字段<br/>如果将内部链接复制并通过QQ、微信、邮件、短信等方式发送给别人，别人是无法访问的这个链接<br/>所以通用规则是：通过社交方式发送给别人的必须使用标准链接，在内部模块中则两种都可以使用，但建议使用内部链接。</em>' +
					'</div>' +
					'');
			});
		},
		pageUnloaded: function (page) {
			var tagName = page.id;
			$('#layout-header > .header > .link').find('.l' + pageIndex(page.id).type).removeClass('on');
			if ($('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName)[0]) {
				$('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName).removeClass('on');
			} else {
				$('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName + '_' + page.params.toString().replace(',', '_')).removeClass('on');
			}
		}
	});
}


/**
 * 布局工具类：作用于全局
 * @type {{}}
 */
var $layout = {};
/**
 * 菜单工具类：作用于模块
 * @type {{}}
 */
var $menu = {};

$menu.selectItem = function (page) {
	var tagName = page.id;
	tagName += page.params[0] || 0;
	$('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName).addClass('on');
};

$menu.unselectItem = function (page) {
	var tagName = page.id;
	tagName += page.params[0] || 0;
	$('.layout-container > .layout-sidebar-left > .item').find('.page-' + tagName).removeClass('on');
};


/**
 * 页面头部
 * @type {{}}
 */
$layout.header = {};

$layout.header.currentType = 0;

$layout.header.doItemClick = function (type, pageId, wxAccountId) {
	if (type == this.currentType) return;
	this.currentType = type;
	this.doInitSidebar(type, pageId, wxAccountId, true);
	$wk.page.load(pageId);
};


$layout.header.doInitSidebar = function (type, pageId, wxAccountId, flag) {
	if (type == 1) {
		$('#layout-header > .header > .link').find('.l1').addClass('on');
		$('#layout-header > .header > .link').find('.l2').removeClass('on');
		$('#layout-header > .header > .link').find('.l2').hide();
	} else if (type == 2) {
		$('#layout-header > .header > .link').find('.l2').show();
		$('#layout-header > .header > .link').find('.l2').addClass('on');
		$('#layout-header > .header > .link').find('.l1').removeClass('on');
	}
	if (!flag && type == this.type) return;
	this.type = type;

	$("#layout-sidebar-left").empty();
	if (type == 1) {
		$menu.platform.init(pageId);
	}
};


$layout.header.doShowItem = function (type) {
	$('#layout-header > .header > .link').find('.l' + type).show();
}

$layout.header.doHideItem = function (type) {
	$('#layout-header > .header > .link').find('.l' + type).hide();
}

$layout.header.doShowAllItem = function () {
	$('#layout-header > .header > .link').show();
}

$layout.header.doHideAllItem = function (type) {
	$('#layout-header > .header > .link').hide();
}

$layout.header.doShowMenu = function () {
	$('#layout-header > .header > .menu').show();
}

$layout.header.doHideMenu = function () {
	$('#layout-header > .header > .menu').hide();
}

$layout.header.show = function () {
	$('#layout-header').show();
};

$layout.header.hide = function () {
	$('#layout-header').hide();
};

/**
 * 设置
 * @param e
 * @param obj
 */
$layout.header.setting = function (e, obj) {
	e.stopPropagation();
	$(obj).parent().next().show();
	$(document).bind('click', function () {
		$(obj).parent().next().hide();
		$(document).unbind('click');
	});
};

/**
 * 页面头部的方法：修改密码
 */
$layout.header.updatePwd = function () {
	//$wk.page.load('update_password');
	$wk.msg.showAlert('此功能暂时未开通');
	//return false;
};

/**
 * 页面头部的方法：退出
 */
$layout.header.exit = function () {
	$wk.msg.showConfirm('是否要退出？', function () {
		$wk.api.showLoading('正在退出...');
		$wk.page.load("login");
		$wk.api.hideLoading();
	});
};

/**
 * 页面底部
 * @type {{}}
 */
$layout.footer = {};

$layout.footer.show = function () {
	$('#layout-footer').show();
};

$layout.footer.hide = function () {
	$('#layout-footer').hide();
};