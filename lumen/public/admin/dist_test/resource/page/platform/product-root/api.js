$wk.api.productRoot = {
	// 获取产品溯源详情
	productTraceabilityGet: function (id,options) {
		$wk.api.post('productTraceabilityGet',{id:parseInt(id)},options);
	},
	// 获取产品溯源列表
	productTraceabilityGetList: function (search,options) {
        $wk.api.post('productTraceabilityGetList', {
            regionId: parseInt(search.regionId),
            countyId: parseInt(search.countyId),
            name: search.name,
            userId: parseInt(search.userId),
            farmId: parseInt(search.farmId),
            productName: search.productName,
            productCategory: parseInt(search.productCategory),
            startAt: parseInt(search.startAt),
            endAt: parseInt(search.endAt),
            pageSize: parseInt(search.pageSize),
            pageNumber: parseInt(search.pageNumber)
        }, options);
	},
}