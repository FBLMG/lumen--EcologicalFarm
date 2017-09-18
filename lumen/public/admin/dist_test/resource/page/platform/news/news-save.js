/**
 * 新闻保存
 */
$wk.page.createPage('news-save', new $wk.page.newPage({
	init: function () {
		this.templateData = {};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="form ui-form2">' +
			'			<div class="box">' +
			'				<div class="vo">' +
			'					<div class="l">新闻标题：</div>' +
			'					<div class="r">' +
			'						<input id="news-save-title" class="ui-input" type="text" placeholder="标题" value="{{news.title}}" style="width: 422px;">' +
			'					</div>' +
			'				</div>' +
			'				<div class="vo">' +
			'					<div class="l">图片：</div>' +
			'					<div class="r">' +
			'						<div class="upload_image">{{#newsSaveImg}}</div>' +
			'					</div>' +
			'				</div>' +	
			'				<div class="vo">' +
			'					<div class="l">新闻内容：</div>' +
			'					<div class="r">' +
			'						<textarea id="news-save-content" class="page-textarea" style="width: 80%;">{{news.content}}</textarea>' +
			'					</div>' +
			'				</div>' +
			'				<div class="vo">' +
			'					<div class="l">视频：</div>' +
			'					<div class="r">' +
			'						<input id="news-save-video" class="ui-input" type="text" placeholder="视频" value="{{news.video}}" style="width: 422px;">' +
			'                       <span class="help">第三方视频地址(http/https 开头)</span>'+
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
		this.templateData.news = {};
		this.templateData.newsId = parseInt(this.params[0] || 0);
	},
	loaded: function () {
		var that = this;
		if (that.templateData.newsId) {
			$wk.api.showLoading();
			$wk.api.news.newsGet(that.templateData.newsId, function (result) {
				console.log(result)
				that.templateData.news = result.data;
				that.loadEdit();
			});
		} else {
			that.loadEdit();
		}
	},
	draw: function () {
		UM.clearCache('news-save-content');
		var that = this;
		var um = UM.getEditor('news-save-content');
		um.addListener('blur', function () {
			that.templateData.news.content = um.getContent();
		});
	}
}, {
	doSave: function () {
		var that = this;
		that.templateData.news.title = $('#news-save-title').val();
		that.templateData.news.video = $('#news-save-video').val();
		if (that.templateData.newsId) { // 编辑
			$wk.api.news.newsUpdate(that.templateData.news, function () {
				$wk.msg.showAlert('编辑成功', function () {
					$wk.page.load('news-list');
				});
			});
		} else { // 添加
			$wk.api.news.newsInsert(that.templateData.news, function () {
				$wk.msg.showAlert('添加成功', function () {
					$wk.page.load('news-list');
				});
			});
		}
	},
	loadEdit: function () {
		var that = this;
		that.templateData.newsSaveImg = $wk.upload_image.echoHtml('newsSaveImg',
			that.templateData.news.images ? [that.templateData.news.images] : [], false, 0,
			function (imgFile) {
				that.templateData.news.images = imgFile;
			},
			function () {
				that.templateData.news.images = null;
			}
		);
		that.drawView(that.parseTemplate(that.template, that.templateData));	
		$wk.api.hideLoading();
	}
}));