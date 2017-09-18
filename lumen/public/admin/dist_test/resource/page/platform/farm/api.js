/**
 * 农场
 */
$wk.api.farm = {
    // 获取农场详情
    farmGet: function(id, options) {
        $wk.api.post('farmGet', {
            id: parseInt(id)
        }, options);
    },
    // 获取农场列表
    farmGetList: function(search, options) {
        $wk.api.post('farmGetList', {
            name: search.name,
            regionId: parseInt(search.regionId),
            countyId: parseInt(search.countyId),
            startAt: parseInt(search.startAt),
            endAt: parseInt(search.endAt),
            pageSize: parseInt(search.pageSize),
            pageNumber: parseInt(search.pageNumber)
        }, options);
    },
    // 添加农场
    farmInsert: function(insert, options) {
        $wk.api.post('farmInsert', {
            name: insert.name,
            images: insert.images,
            content: insert.content,
            video:insert.video,
            regionId: parseInt(insert.regionId),
            countyId: parseInt(insert.countyId),
        }, options);
    },
    // 编辑农场
    farmUpdate: function(update, options) {
        $wk.api.post('farmUpdate', {
            id: parseInt(update.id),
            name: update.name,
            images: update.images,
            content: update.content,
            video: update.video,
            regionId: parseInt(update.regionId),
            countyId: parseInt(update.countyId),
        }, options);
    },
    // 移除农场
    farmDelete: function(id, options) {
        $wk.api.post('farmDelete', {
            id: parseInt(id)
        }, options);
    },
    uploadFile: function (fileType,fileData,fileSuffix) {
                $wk.api.post('uploadFile', {
            fileType: parseInt(fileType),
            fileData: fileData,
            fileSuffix:fileSuffix
        }, options);
    }
}