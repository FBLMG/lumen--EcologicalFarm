/**
 * 新闻
 */
$wk.api.news = {
	// 获取新闻详情
	newsGet : function (id,options) {
		$wk.api.post('newsGet',{id:parseInt(id)},options);
	},
	// 获取新闻列表
	newsGetList: function (search,options) {
		$wk.api.post('newsGetList',{
			title: search.title,
			startAt: parseInt(search.startAt),
			endAt: parseInt(search.endAt),
			endAt: parseInt(search.endAt),
			pageSize: parseInt(search.pageSize),
            pageNumber: parseInt(search.pageNumber)
		},options);
	},
	// 添加新闻
	newsInsert: function (insert,options) {
		$wk.api.post('newsInsert',{
			title: insert.title,
			images: insert.images,
			content: insert.content,
			video: insert.video
		},options);
	},
	// 编辑新闻
	newsUpdate: function (update,options) {
		$wk.api.post('newsUpdate',{
			id: update.id,
			title: update.title,
			images: update.images,
			content: update.content,
			video: update.video
		},options);
	},
	// 移除新闻
	newsDelete: function (id,options) {
		$wk.api.post('newsDelete',{id:parseInt(id)},options);
	}

}