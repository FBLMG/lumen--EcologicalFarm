/**
 * 清除缓存
 */
$wk.page.createPage('clean_cache', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			cleanCacheRst: 0,
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="page-nav ui-nav">' +
			'			<div class="left">' +
			'				<a class="ui-btn" style="background-color: #00a5ed" onclick="{{$page}}.doClean(event, this);">清除缓存</a>' +
			'               <span>清除系统所有缓存</span>' +
			'			</div>' +
			'		</div>' +
			'   </div>' +
			'</div>' +
			'';
	},
	loaded: function () {
		var that = this;
		that.drawView(that.parseTemplate(that.template, that.templateData));
	}
}, {
	/**
	 * 执行
	 */
	doClean: function () {
		$wk.msg.showConfirm('确定清除缓存吗？', function () {
			$wk.api.cache.cleanCache(function () {
				$wk.msg.showAlert('缓存清除成功');
			});
		});
	}
}));

