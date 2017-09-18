//首页部分的菜单
$menu.platform = {};

//当前页面的id
$menu.platform.currentPageId = null;

//初始化
$menu.platform.init = function (pageId) {
	if (this.currentPageId == pageId) return;
	var userPower = parseInt($wk.api.getUserPower());
	var menuHTML = '';
	menuHTML += '<div class="item">';
	if (userPower === 1) { 
		// -----------------------------------------------------------------------------------------------------------------
		// 农场
		// -----------------------------------------------------------------------------------------------------------------
		menuHTML += '	<div class="txt" onclick="$(this).next().slideToggle(500);">农场</div>';
		menuHTML += '	<ul class="menu menu-farm" style="display:none;">';
		menuHTML += '		<li class="no-bg">';
		menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">农场管理</div>';
		menuHTML += '			<ul class="sub-menu sub-menu-farm" style="display:none;">';
		menuHTML += '				<li class="page-farm-list"><a href="' + $wk.page.href('farm-list') + '")">农场列表<i></i></a></li>';
		menuHTML += '				<li class="page-farm-save"><a href="' + $wk.page.href('farm-save') + '")">农场添加<i></i></a></li>';
		menuHTML += '			</ul>';
		menuHTML += '		</li>';
		menuHTML += '	</ul>';
		// -----------------------------------------------------------------------------------------------------------------
		// 新闻
		// -----------------------------------------------------------------------------------------------------------------
		menuHTML += '	<div class="txt" onclick="$(this).next().slideToggle(500);">新闻</div>';
		menuHTML += '	<ul class="menu menu-news" style="display:none;">';
		menuHTML += '		<li class="no-bg">';
		menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">新闻管理</div>';
		menuHTML += '			<ul class="sub-menu sub-menu-news" style="display:none;">';
		menuHTML += '				<li class="page-news-list"><a href="' + $wk.page.href('news-list') + '")">新闻列表<i></i></a></li>';
		menuHTML += '				<li class="page-news-save"><a href="' + $wk.page.href('news-save') + '")">新闻添加<i></i></a></li>';
		menuHTML += '			</ul>';
		menuHTML += '		</li>';
		menuHTML += '	</ul>';
		// -----------------------------------------------------------------------------------------------------------------
		// 产品溯源
		// -----------------------------------------------------------------------------------------------------------------
		menuHTML += '	<div class="txt" onclick="$(this).next().slideToggle(500);">产品溯源</div>';
		menuHTML += '	<ul class="menu menu-product-root" style="display:none;">';
		menuHTML += '		<li class="no-bg">';
		menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">产品溯源管理</div>';
		menuHTML += '			<ul class="sub-menu sub-menu-product-root" style="display:none;">';
		menuHTML += '				<li class="page-product-root-list"><a href="' + $wk.page.href('product-root-list') + '")">产品溯源列表<i></i></a></li>';
		menuHTML += '			</ul>';
		menuHTML += '		</li>';
		menuHTML += '	</ul>';
		// -----------------------------------------------------------------------------------------------------------------
		// 地区
		// -----------------------------------------------------------------------------------------------------------------
		menuHTML += '	<div class="txt" onclick="$(this).next().slideToggle(500);">地区</div>';
		menuHTML += '	<ul class="menu menu-area" style="display:none;">';
		menuHTML += '		<li class="no-bg">';
		menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">地区管理</div>';
		menuHTML += '			<ul class="sub-menu sub-menu-area" style="display:none;">';
		menuHTML += '				<li class="page-high-area-list"><a href="' + $wk.page.href('high-area-list') + '")">地区列表<i></i></a></li>';
		menuHTML += '				<li class="page-high-area-save"><a href="' + $wk.page.href('high-area-save') + '")">地区添加<i></i></a></li>';
		menuHTML += '			</ul>';
		menuHTML += '		</li>';
		menuHTML += '	</ul>';
		// -----------------------------------------------------------------------------------------------------------------
		// 用户
		// -----------------------------------------------------------------------------------------------------------------
		menuHTML += '	<div class="txt" onclick="$(this).next().slideToggle(500);">用户</div>';
		menuHTML += '	<ul class="menu menu-users" style="display:none;">';
		menuHTML += '		<li class="no-bg">';
		menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">用户管理</div>';
		menuHTML += '			<ul class="sub-menu sub-menu-users" style="display:none;">';
		menuHTML += '				<li class="page-users-list"><a href="' + $wk.page.href('users-list') + '")">用户列表<i></i></a></li>';
		menuHTML += '			</ul>';
		menuHTML += '		</li>';
		menuHTML += '	</ul>';
		// -----------------------------------------------------------------------------------------------------------------
		// 系统
		// -----------------------------------------------------------------------------------------------------------------
		menuHTML += '	<div class="txt" onclick="$(this).next().slideToggle(500);">系统</div>';
		menuHTML += '	<ul class="menu menu-system" style="display:none;">';
		menuHTML += '		<li class="no-bg">';
		menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">缓存管理</div>';
		menuHTML += '			<ul class="sub-menu sub-menu-cache" style="display:none;">';
		menuHTML += '				<li class="page-clean_cache"><a href="' + $wk.page.href('clean_cache') + '")">清除所有缓存<i></i></a></li>';
		menuHTML += '			</ul>';
		menuHTML += '		</li>';
		// menuHTML += '		<li class="no-bg">';
		// menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">变量管理</div>';
		// menuHTML += '			<ul class="sub-menu sub-menu-variable" style="display:none;">';
		// menuHTML += '				<li class="page-variable_list"><a href="' + $wk.page.href('variable_list') + '")">变量列表<i></i></a></li>';
		// menuHTML += '			</ul>';
		// menuHTML += '		</li>';\
		menuHTML += '		<li class="no-bg">';
		menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">管理员管理</div>';
		menuHTML += '			<ul class="sub-menu sub-menu-admin" style="display:none;">';
		menuHTML += '				<li class="page-admin-list"><a href="' + $wk.page.href('admin-list') + '")">管理员列表<i></i></a></li>';
		menuHTML += '			</ul>';
		menuHTML += '		</li>';
		// menuHTML += '		<li class="no-bg">';
		// menuHTML += '			<div class="sub-txt" onclick="$(this).next().slideToggle(200);">访问管理</div>';
		// menuHTML += '			<ul class="sub-menu sub-menu-total" style="display:none;">';
		// menuHTML += '				<li class="page-toal_list"><a href="' + $wk.page.href('total_list') + '")">访问列表<i></i></a></li>';
		// menuHTML += '			</ul>';
		// menuHTML += '		</li>';
		menuHTML += '	</ul>';
		menuHTML += '   </div>';
		menuHTML += '</div>';
		menuHTML += '';
	}
	$("#layout-sidebar-left").append(menuHTML);
};


$menu.platform.select = function (pageId) {
};

$menu.indexClickEvent = function (obj, pageId) {
};
