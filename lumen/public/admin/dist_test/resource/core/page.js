/**
 * 页面
 * @type {{}}
 */
$wk.page = {
	/**
	 * 所有页面集合
	 * @type {{}}
	 */
	pageList: {},

	/**
	 * 当前页面对象
	 * @type {null}
	 */
	pageCurrent: null,

	/**
	 * 上个页面对象
	 * @type {null}
	 */
	pageLast: null,

	/**
	 * 当前路由
	 * @type {string}
	 */
	routeCurrent: '',

	/**
	 * 上个路由
	 * @type {string}
	 */
	routeLast: '',

	/**
	 * 初始化的URL
	 */
	initURL: window.location.href
};

/**
 * 初始化Options
 * @param options object 属性参数 {
 * 		pageRootClass: 'page-root',
 * 		pageNodeClass: 'page-node',
 * 		pageCurrentClass: 'page-current',
 * 		elementRoot: document.body,
 * 		pageInit: null,
 * 		pageLoad: null,
 * 		pageLoaded: null,
 * 		pageUnload: null,
 * 		pageUnloaded: null
 * 	}
 * @private
 */
$wk.page.__initOptions = function (options) {
	/**
	 * 检验一下options
	 */
	options = options || {};

	/**
	 * 属性
	 * @type {{pageRootClass: (*|string), pageNodeClass: (*|string), pageCurrentClass: (*|string)}}
	 */
	this.options = {
		/**
		 * 页面的样式
		 */
		pageRootClass: options.pageRootClass || 'page-root',
		/**
		 * 页面的Node元素样式
		 * 那什么是Node呢？Node是Page下面的一个元素，如果有顶部导航或底部菜单的情况下，它就有用处了
		 */
		pageNodeClass: options.pageNodeClass || 'page-node',
		/**
		 * 当前页面的样式
		 */
		pageCurrentClass: options.pageCurrentClass || 'page-current',
		/**
		 * 当前页面的初始样式
		 */
		pageInitClass: options.pageInitClass || 'page-init',
		/**
		 * 页面初始化
		 */
		pageInit: options.pageInit || null,
		/**
		 * 页面加载
		 */
		pageLoad: options.pageLoad || null,
		/**
		 * 页面加载完成
		 */
		pageLoaded: options.pageLoaded || null,
		/**
		 * 页面重绘
		 */
		pageDraw: options.pageDraw || null,
		/**
		 * 页面卸载
		 */
		pageUnload: options.pageUnload || null,
		/**
		 * 页面卸载完成
		 */
		pageUnloaded: options.pageUnloaded || null
	};

	/**
	 * 根元素
	 * 所有创建的页面元素都会被初始化至此标签中，默认为body
	 */
	this.elementRoot = options.elementRoot || document.body;

	/**
	 * 元素模式元素
	 * 用于防止页面执行动画的过程中，用户触发了影响动画的事件
	 * @type {HTMLElement}
	 */
	this.elementModel = document.createElement('DIV');

	/**
	 * 初始化模式元素的样式
	 * @type {string}
	 */
	this.elementModel.className = 'page-model';
	this.elementRoot.appendChild(this.elementModel);

	/**
	 * 格式化所有页面数据
	 */
	for (var a in this.pageList) {
		if (this.elementRoot && !document.getElementById(a)) {
			this.pageList[a].id = a;
			this.pageList[a].elementRoot.id = a;
			this.pageList[a].elementRoot.className = this.options.pageRootClass;
			this.pageList[a].elementNode.className = this.options.pageNodeClass;
			this.elementRoot.appendChild(this.pageList[a].elementRoot);
		}
	}
};

/**
 * 监控路由
 * @param defaultId string 默认页面ID
 * @param defaultParams array 默认页面参数
 * @private
 */
$wk.page.__monitorRoute = function (defaultId, defaultParams) {
	var that = this;
	var routeInit = false;
	setInterval(function () {
		var data, id, params;
		var href = window.location.href;
		var route = href.indexOf('#') === -1 ? '' : href.substring(href.indexOf('#') + 2, href.length);
		if (route !== that.routeLast) {
			if (route === '') {
				// 如果路由为空，则表示突然返回到入口
				that.load(defaultId, defaultParams, 0, false);
			} else {
				// 如果路由改变，则解析新的路由
				data = route.split('/');
				params = [];
				for (var a in data) {
					if (a == 0) {
						id = data[0];
					} else {
						params.push(data[a]);
					}
				}
				that.load(id, params, 0, true);
			}
		} else if (route === '' && routeInit === false) {
			that.load(defaultId, defaultParams, 0, false);
			routeInit = true;
		}
		that.routeLast = route;
	}, 50);
};

/**
 * 页面转换为HREF
 * 此方法用于重新设置当前路由
 * @param id string 页面ID
 * @param params array 页面参数
 * @returns {string}
 * @private
 */
$wk.page.__page2href = function (id, params) {
	var href = '#/' + id;
	for (var a in params) {
		href += '/' + params[a];
	}
	return href;
};

/**
 * 加载页面
 * @param page object 目标页面对象
 * @param params array 目标页面加载时的接收参数，如果开启路由功能，则属于路由的一部分
 * @param data unknown 目标页面加载时的接收数据，临时的数据，刷新后将不复存在
 * @param options object 目标页面加载时的相关属性 {
 * 		pageScrollTop: -1,
 * 		pageAppearClassName: '',
 * 		pageAppearTime: 0,
 * 		pageDisappearClassName: '',
 * 		pageDisappearTime: 0,
 * 		pageRefreshRoute: true,
 * 		pageLoadStart: null,
 * 		pageLoadEnd: null
 * 	}
 */
$wk.page.__loadPage = function (page, params, data, options) {
	/**
	 * 错误检查
	 */
	if (page === null || page === undefined) {
		return $wk.log('错误：页面不存在，无法加载');
	}

	/**
	 * 相关变量定义
	 */
	var that = this;
	var optionsObj = options || {
			pageScrollTop: -1,
			pageAppearClassName: '',
			pageAppearTime: 0,
			pageDisappearClassName: '',
			pageDisappearTime: 0,
			pageRefreshRoute: true,
			pageLoadStart: null,
			pageLoadEnd: null
		}; // 配置项
	var paramsObj = params || []; // 参数
	var pageScrollTop = optionsObj.pageScrollTop === undefined ? -1 : optionsObj.pageScrollTop; // 页面滚动条的TOP值
	var pageAppearClassName = optionsObj.pageAppearClassName || ''; // 页面显示样式名称
	var pageAppearTime = optionsObj.pageAppearTime || 0; // 页面显示时间
	var pageDisappearClassName = optionsObj.pageDisappearClassName || ''; // 页面消失样式名称
	var pageDisappearTime = optionsObj.pageDisappearTime || 0; // 页面消失样式时间
	var pageRefreshRoute = optionsObj.pageRefreshRoute === undefined ? true : optionsObj.pageRefreshRoute; // 页面刷新路由
	var pageLoadStart = optionsObj.pageLoadStart || null; // 页面加载前的执行方法
	var pageLoadEnd = optionsObj.pageLoadEnd || null; // 页面加载后的执行方法

	/**
	 * 如果加载的目标页面对象和当前页面对象是同一个，则不作任何处理
	 */
	if (page === that.pageCurrent && paramsObj.toString() === that.routeCurrent) {
		return $wk.log('注意：同一个页面，无需加载');
	}

	/**
	 * 加入事件阻止层
	 */
	$(that.elementModel).show();

	/**
	 * 执行消失回调方法（当前页面）
	 */
	if (that.pageLast = that.pageCurrent) {
		if (that.pageLast !== page && CONFIG_ANIMATION && pageDisappearClassName) { // 有消失动画
			$(that.pageLast.elementRoot).removeClass(that.options.pageInitClass).addClass(that.options.pageCurrentClass).addClass(pageDisappearClassName);
			that.pageLast.pageUnload && that.pageLast.pageUnload();
			that.options.pageUnload && that.options.pageUnload(that.pageLast);
			setTimeout(function () {
				$(that.pageLast.elementRoot).removeClass(that.options.pageCurrentClass).removeClass(pageDisappearClassName);
				that.pageLast.pageUnloaded && that.pageLast.pageUnloaded();
				that.options.pageUnloaded && that.options.pageUnloaded(that.pageLast);
			}, pageDisappearTime);
		} else { // 无消失动画
			$(that.pageLast.elementRoot).removeClass(that.options.pageInitClass).removeClass(that.options.pageCurrentClass);
			that.pageLast.pageUnload && that.pageLast.pageUnload();
			that.options.pageUnload && that.options.pageUnload(that.pageLast);
			that.pageLast.pageUnloaded && that.pageLast.pageUnloaded();
			that.options.pageUnloaded && that.options.pageUnloaded(that.pageLast);
		}
	}

	/**
	 * 标记当前页面对象为目标页面对象，并保存参数及数据，以便在其它生命周期中可以访问
	 */
	that.pageCurrent = page;
	that.pageCurrent.params = paramsObj;
	that.pageCurrent.data = data;
	that.routeCurrent = paramsObj.toString();

	/**
	 * 目标页面执行前的回调方法
	 */
	if (typeof pageLoadStart === 'function') {
		pageLoadStart(that);
	}

	/**
	 * 标记页面加载进入时的路由状态
	 */
	if (that.pageCurrent.eqStatusLoadOrPush()) {
		that.pageCurrent.__route = pageRefreshRoute;
	}

	/**
	 * 重置路由
	 */
	if (pageRefreshRoute) {
		window.location.href = this.routeLast = that.__page2href(page.id, paramsObj);
	}

	/**
	 * 执行初始化回调方法（目标页面）
	 */
	if (that.pageCurrent.__init === undefined) {
		// 如果目标页面没有初始化，则状态必须设置为LAOD状态
		$(that.pageCurrent.elementRoot).addClass(that.options.pageInitClass);
		that.pageCurrent.setStatus(that.STATUS_LOAD);
		that.pageCurrent.pageInit && that.pageCurrent.pageInit();
		that.options.pageInit && that.options.pageInit(that.pageCurrent);
	}
	that.pageCurrent.__init = true;

	/**
	 * 执行显示回调方法（目标页面）
	 */
	if (that.pageLast !== page && CONFIG_ANIMATION && pageAppearClassName) { // 有显示动画
		$(that.pageCurrent.elementRoot).addClass(that.options.pageCurrentClass).addClass(pageAppearClassName);
		that.pageCurrent.pageLoad && that.pageCurrent.pageLoad();
		that.options.pageLoad && that.options.pageLoad(that.pageCurrent);
		setTimeout(function () {
			$(that.elementModel).hide(); // 删除事件阻止层
			$(that.pageCurrent.elementRoot).removeClass(pageAppearClassName);
			that.pageCurrent.pageLoaded && that.pageCurrent.pageLoaded();
			that.options.pageLoaded && that.options.pageLoaded(that.pageCurrent);
			typeof pageLoadEnd === 'function' && pageLoadEnd(that);
		}, pageAppearTime);
	} else { // 无显示动画
		$(that.elementModel).hide(); // 删除事件阻止层
		$(that.pageCurrent.elementRoot).addClass(that.options.pageCurrentClass);
		that.pageCurrent.pageLoad && that.pageCurrent.pageLoad();
		that.options.pageLoad && that.options.pageLoad(that.pageCurrent);
		that.pageCurrent.pageLoaded && that.pageCurrent.pageLoaded();
		that.options.pageLoaded && that.options.pageLoaded(that.pageCurrent);
		typeof pageLoadEnd === 'function' && pageLoadEnd(that);
	}

	/**
	 * 给当前页面添加一个ROOT级样式名，用于构建当前页面的特殊样式
	 */
	if (that.pageLast) {
		if ($(that.pageLast.elementRoot).hasClass('page-nav') && $(that.pageCurrent.elementRoot).hasClass('page-nav')) {
			$(that.elementRoot).removeClass('page-nav-' + that.pageLast.id);
		} else {
			setTimeout(function () {
				$(that.elementRoot).removeClass('page-nav-' + that.pageLast.id);
			}, 500);
		}
		if ($(that.pageLast.elementRoot).hasClass('page-tabs') && $(that.pageCurrent.elementRoot).hasClass('page-tabs')) {
			$(that.elementRoot).removeClass('page-tabs-' + that.pageLast.id);
		} else {
			setTimeout(function () {
				$(that.elementRoot).removeClass('page-tabs-' + that.pageLast.id);
			}, 500);
		}
		if ($(that.pageLast.elementRoot).hasClass('page-tabtops') && $(that.pageCurrent.elementRoot).hasClass('page-tabtops')) {
			$(that.elementRoot).removeClass('page-tabtops-' + that.pageLast.id);
		} else {
			setTimeout(function () {
				$(that.elementRoot).removeClass('page-tabtops-' + that.pageLast.id);
			}, 500);
		}
	}
	if ($(that.pageCurrent.elementRoot).hasClass('page-nav')) {
		$(that.elementRoot).addClass('page-nav-' + that.pageCurrent.id);
	}
	if ($(that.pageCurrent.elementRoot).hasClass('page-tabs')) {
		$(that.elementRoot).addClass('page-tabs-' + that.pageCurrent.id);
	}
	if ($(that.pageCurrent.elementRoot).hasClass('page-tabtops')) {
		$(that.elementRoot).addClass('page-tabtops-' + that.pageCurrent.id);
	}

	/**
	 * 初始化当前页面的滚动条的TOP值
	 */
	if (pageScrollTop >= 0) {
		that.pageCurrent.elementRoot.scrollTop = pageScrollTop;
	}
};

/**
 * 根据页面ID获取页面对象
 * @param id
 * @returns {*}
 */
$wk.page.__getPageWithId = function (id) {
	if (this.pageList[id] !== null) {
		return this.pageList[id];
	} else {
		$wk.log('错误：没有获取到页面 [ ' + id + ' ]');
	}
};

/**
 * 根据页面ID加载页面
 * @param id
 * @param params
 * @param data
 * @param options
 */
$wk.page.__loadPageWithId = function (id, params, data, options) {
	var page = this.__getPageWithId(id);
	if (page) {
		this.__loadPage(page, params, data, options);
	}
};

/**
 * 获取一个Route级的父页面对象
 * @param page
 * @param defaultId
 * @returns {*}
 * @private
 */
$wk.page.__getRouteParentPage = function (page, defaultId) {
	if (typeof defaultId === 'string' && defaultId.substr(0, 1) === '#') { // 强制加载目标页面
		return this.__getPageWithId(defaultId.substr(1));
	}
	if (page.parent) {
		if (page.parent.__route) {
			return page.parent;
		} else {
			return this.__getRouteParentPage(page.parent, defaultId);
		}
	} else {
		return this.__getPageWithId(defaultId);
	}
};

/**
 * 获取一个任意的父页面对象
 * @param page
 * @param defaultId
 * @returns {*}
 * @private
 */
$wk.page.__getAnyParentPage = function (page, defaultId) {
	if (typeof defaultId === 'string' && defaultId.substr(0, 1) === '#') { // 强制加载目标页面
		return this.__getPageWithId(defaultId.substr(1));
	}
	if (page.parent) {
		return page.parent;
	} else {
		return this.__getPageWithId(defaultId);
	}
};

/**
 * 设置父页面对象
 * @private
 */
$wk.page.__setParentPage = function () {
	if (this.pageLast !== this.pageCurrent) {
		this.pageCurrent.parent = this.pageLast;
	}
};

/**
 * 格式化加载页面的Options选项
 * @param options
 * @param loadPageType
 * @param animationType
 * @private
 */
$wk.page.__formatLoadPageOptions = function (options, loadPageType, animationType) {
	var pushAnimationTypes = [{ // 无动画
		pageAppearClassName: '',
		pageAppearTime: 0,
		pageDisappearClassName: '',
		pageDisappearTime: 0
	}, { // 左右动画
		pageAppearClassName: 'page-ani-push-load-lr',
		pageAppearTime: 500,
		pageDisappearClassName: 'page-ani-push-unload-lr',
		pageDisappearTime: 500
	}, { // 上下动画
		pageAppearClassName: 'page-ani-push-load-ud',
		pageAppearTime: 500,
		pageDisappearClassName: 'normal',
		pageDisappearTime: 500
	}];
	var popAnimationTypes = [{ // 无动画
		pageAppearClassName: '',
		pageAppearTime: 0,
		pageDisappearClassName: '',
		pageDisappearTime: 0
	}, { // 左右动画
		pageAppearClassName: 'page-ani-pop-load-lr',
		pageAppearTime: 500,
		pageDisappearClassName: 'page-ani-pop-unload-lr',
		pageDisappearTime: 500
	}, { // 上下动画
		pageAppearClassName: 'normal',
		pageAppearTime: 500,
		pageDisappearClassName: 'page-ani-pop-load-ud',
		pageDisappearTime: 500
	}];
	if (loadPageType === 'push') {
		return $.extend(options, pushAnimationTypes[animationType]);
	} else if (loadPageType === 'pop') {
		return $.extend(options, popAnimationTypes[animationType]);
	}
	return options;
};

/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////

/**
 * 模态动画类型常量
 */
$wk.page.ANIMATION_NO = 0; // 无动画
$wk.page.ANIMATION_LR = 1; // 左右滑动
$wk.page.ANIMATION_UD = 2; // 上下滑动
$wk.page.STATUS_LOAD = 0; // 加载状态（默认）
$wk.page.STATUS_PUSH = 1; // 加载状态（PUSH）
$wk.page.STATUS_POP = 2; // 加载状态（POP）

/**
 * 页面对象
 * 使用方法：$wk.page.createPage('home', $wk.page.newPage({init:function() {}}));
 * @param options
 * @param extend
 */
$wk.page.newPage = function (options, extend) {
	/**
	 * ID，对应元素的ID
	 * @type {string}
	 */
	this.id = '';
	/**
	 * 参数
	 * @type {Array}
	 */
	this.params = [];
	/**
	 * 数据
	 * @type {{}}
	 */
	this.data = {};
	/**
	 * 父级页面
	 * @type {null}
	 */
	this.parent = null;
	/**
	 * 页面根元素
	 * @type {Element}
	 */
	this.elementRoot = document.createElement('DIV');
	/**
	 * 页面节点元素（编辑页面内容请使用此对象）
	 * @type {Node}
	 */
	this.elementNode = this.elementRoot.appendChild(document.createElement('DIV'));
	/**
	 * 页面初始化方法
	 */
	this.pageInit = options.init;
	/**
	 * 页面将要出现时执行的方法
	 */
	this.pageLoad = options.load;
	/**
	 * 页面出现完成时执行的方法
	 */
	this.pageLoaded = options.loaded;
	/**
	 * 页面将要消失时执行的方法
	 */
	this.pageUnload = options.unload;
	/**
	 * 页面消失完成时执行的方法
	 */
	this.pageUnloaded = options.unloaded;
	/**
	 * 页页重绘时执行的方法
	 */
	this.pageDraw = options.draw;
	/**
	 * 解析模板和数据
	 * @param html HTML模板数据
	 * @param data 数据
	 */
	this.parseTemplate = function (html, data) {
		html = html.replace(/\{\{\$page}}/g, '$wk.page.getPage(\'' + this.id + '\')');
		return template.compile(html)(data);
	};
	/**
	 * 重绘视图
	 * @param html
	 */
	this.drawView = function (html) {
		this.elementNode.innerHTML = html;
		if ($(this.elementRoot).hasClass($wk.page.options.pageInitClass)) {
			$(this.elementNode).addClass('page-ani-init');
		}
		this.pageDraw && this.pageDraw();
		$wk.page.options.pageDraw(this);
		return this;
	};
	/**
	 * 检查加载状态
	 * @param status
	 */
	this.eqStatus = function (status) {
		return this.__status === status;
	};
	/**
	 * 检查加载状态是否是LOAD或PUSH状态
	 * 通过此项检查可以设置页面POP回来的时候不做网络请求
	 */
	this.eqStatusLoadOrPush = function () {
		return this.__status === $wk.page.STATUS_LOAD || this.__status === $wk.page.STATUS_PUSH;
	};
	/**
	 * 设置加载状态
	 * @param status
	 * @returns {$wk.page.newPage}
	 */
	this.setStatus = function (status) {
		this.__status = status;
		return this;
	};
	/**
	 * 滚动条至目标位置
	 * @param top
	 */
	this.scrollTop = function (top) {
		this.elementRoot.scrollTop = top;
		return this;
	};
	/**
	 * 扩展页面对象
	 */
	$.extend(this, extend || {});
};

/**
 * 创建APP
 * @param defaultId string 默认页面ID
 * @param defaultParams array 默认页面参数
 * @param options object 属性参数，参考__initOptions方法的说明
 * @returns {$wk.page}
 */
$wk.page.createApp = function (defaultId, defaultParams, options) {
	this.__initOptions(options);
	this.__monitorRoute(defaultId, defaultParams);
	return this;
};

/**
 * 创建页面
 * @param id string 页面ID
 * @param page object 页面对象，使用$wk.page.newPage方法创建的对象
 * @returns {$wk.page}
 */
$wk.page.createPage = function (id, page) {
	this.pageList[id] = page;
	return this;
};

/**
 * 获取页面
 * @param id
 * @returns {*}
 */
$wk.page.getPage = function (id) {
	return this.__getPageWithId(id);
};

/**
 * HREF格式化
 * @param id
 * @param params
 * @returns {string}
 */
$wk.page.href = function (id, params) {
	return this.__page2href(id, params);
};

/**
 * 根据页面ID加载页面
 * @param id string 页面ID
 * @param params array 目标页面加载时的接收参数
 * @param scrollTop number 页面进入时的滚动高度，默认使用页面之前的滚动高度
 * @param refreshRoute boolean 是否刷新路由
 */
$wk.page.load = function (id, params, scrollTop, refreshRoute) {
	this.__loadPageWithId(id, params, {}, {
		pageScrollTop: scrollTop || 0,
		pageRefreshRoute: refreshRoute,
		pageLoadStart: function (that) {
			that.__setParentPage();
			that.pageCurrent.setStatus(that.STATUS_LOAD);
		}
	});
};

/**
 * 根据页面ID返回页面
 * @param id
 * @param params
 * @param scrollTop
 */
$wk.page.back = function (id, params, scrollTop) {
	var __parentPage = this.__getRouteParentPage(this.pageCurrent, id);
	this.__loadPageWithId(__parentPage.id, params || __parentPage.params, __parentPage.data, {
		pageScrollTop: scrollTop,
		pageLoadStart: function (that) {
			that.pageCurrent.setStatus(that.STATUS_POP);
		}
	});
};

/**
 * 根据页面ID加载页面（push动画）
 * @param id string 页面ID
 * @param params array 目标页面加载时的接收参数
 * @param scrollTop number 页面进入时的滚动高度，默认使用页面之前的滚动高度
 * @param animationType number 动画类型
 */
$wk.page.push = function (id, params, scrollTop, animationType) {
	var __animationType = animationType || this.ANIMATION_LR;
	var __animationClass = 'body-page-push-ani' + __animationType;
	this.__loadPageWithId(id, params, {}, this.__formatLoadPageOptions({
		pageScrollTop: scrollTop || 0,
		pageLoadStart: function (that) {
			that.__setParentPage();
			that.pageCurrent.setStatus(that.STATUS_PUSH);
			CONFIG_ANIMATION && $(document.body).addClass(__animationClass);
		},
		pageLoadEnd: function () {
			CONFIG_ANIMATION && $(document.body).removeClass(__animationClass);
		}
	}, 'push', __animationType));
};

/**
 * 根据页面ID加载页面（pop动画）
 * @param id string 页面ID
 * @param params array 目标页面加载时的接收参数
 * @param scrollTop number 页面进入时的滚动高度，默认使用页面之前的滚动高度
 * @param animationType number 动画类型
 */
$wk.page.pop = function (id, params, scrollTop, animationType) {
	var __animationType = animationType || this.ANIMATION_LR;
	var __animationClass = 'body-page-pop-ani' + __animationType;
	var __parentPage = this.__getRouteParentPage(this.pageCurrent, id);
	this.__loadPageWithId(__parentPage.id, params || __parentPage.params, __parentPage.data, this.__formatLoadPageOptions({
		pageScrollTop: scrollTop,
		pageLoadStart: function (that) {
			that.pageCurrent.setStatus(that.STATUS_POP);
			CONFIG_ANIMATION && $(document.body).addClass(__animationClass);
		},
		pageLoadEnd: function () {
			CONFIG_ANIMATION && $(document.body).removeClass(__animationClass);
		}
	}, 'pop', __animationType));
};

/**
 * 根据页面ID加载页面（push动画）
 * @param id string 页面ID
 * @param data object 目标页面加载时的接收数据，临时的数据，刷新后将不复存在
 * @param scrollTop number 页面进入时的滚动高度，默认使用页面之前的滚动高度
 * @param animationType number 动画类型
 */
$wk.page.pushModel = function (id, data, scrollTop, animationType) {
	var __animationType = animationType || this.ANIMATION_UD;
	var __animationClass = 'body-page-push-ani' + __animationType;
	this.__loadPageWithId(id, [], data, this.__formatLoadPageOptions({
		pageScrollTop: scrollTop || 0,
		pageRefreshRoute: false,
		pageLoadStart: function (that) {
			that.__setParentPage();
			that.pageCurrent.setStatus(that.STATUS_PUSH);
			CONFIG_ANIMATION && $(document.body).addClass(__animationClass);
		},
		pageLoadEnd: function () {
			CONFIG_ANIMATION && $(document.body).removeClass(__animationClass);
		}
	}, 'push', __animationType));
};

/**
 * 根据页面ID加载页面（pop动画）
 * @param id string 页面ID
 * @param data object 目标页面加载时的接收数据，临时的数据，刷新后将不复存在
 * @param scrollTop number 页面进入时的滚动高度，默认使用页面之前的滚动高度
 * @param animationType number 动画类型
 */
$wk.page.popModel = function (id, data, scrollTop, animationType) {
	var __animationType = animationType || this.ANIMATION_UD;
	var __animationClass = 'body-page-pop-ani' + __animationType;
	var __parentPage = this.__getAnyParentPage(this.pageCurrent, id);
	this.__loadPageWithId(__parentPage.id, __parentPage.params, data || __parentPage.data, this.__formatLoadPageOptions({
		pageScrollTop: scrollTop,
		pageRefreshRoute: false,
		pageLoadStart: function (that) {
			that.pageCurrent.setStatus(that.STATUS_POP);
			CONFIG_ANIMATION && $(document.body).addClass(__animationClass);
		},
		pageLoadEnd: function () {
			CONFIG_ANIMATION && $(document.body).removeClass(__animationClass);
		}
	}, 'pop', __animationType));
};