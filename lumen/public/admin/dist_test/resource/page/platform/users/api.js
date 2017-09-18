/**
 * 用户管理的Api:
 */
$wk.api.users = {
    // 获取前端用户详情
    userGet: function(id, options) {
        $wk.api.post('userGet', { id: parseInt(id) }, options);
    },
    // 获取前端用户列表
    userGetList: function(search,options) {
        $wk.api.post('userGetList', {
            name: search.name,
            power: parseInt(search.power),
            level: parseInt(search.level),
            status: parseInt(search.status),
            startAt: parseInt(search.startAt),
            endAt: parseInt(search.endAt),
            pageNumber: parseInt(search.pageNumber),
            pageSize: parseInt(search.pageSize)
        }, options);
    },
    // 修改前端用户级别
    userUpdateLevel: function(id,level, options) {
        $wk.api.post('userUpdateLevel', {
            id: parseInt(id),
            level: parseInt(level),
        }, options);
    },
    // 修改前端用户权限
    userUpdatepower: function(id,farmId,options) {
        $wk.api.post('userUpdatepower', {
            id: parseInt(id),
            farmId: parseInt(farmId),
        }, options);
    }

};