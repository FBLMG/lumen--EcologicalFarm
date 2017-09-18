/**
 * 地区
 */
$wk.api.area = {
    // 获取地区名称
    regionGet: function(id, options) {
        $wk.api.post('regionGet', { id: parseInt(id) }, options);
    },
    // ----------------上级地区----------------
    // 获取地区列表
    regionGetList: function(search, options) {
        $wk.api.post('regionGetList', {
            name: search.name,
            startAt: parseInt(search.startAt),
            endAt: parseInt(search.endAt),
            pageSize: parseInt(search.pageSize),
            pageNumber: parseInt(search.pageNumber)
        }, options);
    },
    // 添加上级地区
    parentRegionInsert: function(insert, options) {
        $wk.api.post('parentRegionInsert', {
            name: insert.name
        }, options);
    },
    // 编辑上级地区
    parentRegionUpdate: function (update,options) {
        $wk.api.post('parentRegionUpdate', { 
            id: parseInt(update.id),
            name: update.name
        }, options);
    },
    // 移除上级地区
    parentRegionDelete: function (id,options) {
        $wk.api.post('parentRegionDelete', { id: parseInt(id) }, options);
    },
    // ----------------下级地区----------------
    // 获取子地区列表
    subRegionGet: function (search,options) {
        $wk.api.post('subRegionGet', { 
            id: parseInt(search.regionId),
            name: search.name,
            startAt: parseInt(search.startAt),
            endAt: parseInt(search.endAt),
            pageSize: parseInt(search.pageSize)
        }, options);
    },
    // 添加下级地区
    subRegionInsert: function (insert,options) {
        $wk.api.post('subRegionInsert', { 
            regionId: parseInt(insert.regionId),
            name: insert.name
        }, options);
    },
    // 编辑下级地区
    subRegionUpdate: function (update,options) {
        $wk.api.post('subRegionUpdate', { 
            id: parseInt(update.id),
            name: update.name
        }, options);
    },
    // 移除下级地区
    subRegionDelete: function (id,options) {
        $wk.api.post('subRegionDelete', { id: parseInt(id) }, options);
    }
}