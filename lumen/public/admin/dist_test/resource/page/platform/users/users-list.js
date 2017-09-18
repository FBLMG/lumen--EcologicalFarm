/**
 * 用户列表
 */
$wk.page.createPage('users-list', new $wk.page.newPage({
    init: function () {
        this.templateData = {
            powerLabel: ['', '农场主', '会员'],
            levelLabel: ['', '皇冠会员', '钻石会员', '星级会员'],
            sexLabel: ['未知','男','女','未知'],
            search: {            
                name: '',
                power: 0,
                level: 0,
                status: 0,
                startAt: 0,
                endAt: 0,
                pageSize: 10,
                pageNumber: 0,
            }

        };
        this.template = '' +
            '<div class="page-view">' +
            '	<div class="page-right">' +
            '		<div class="page-nav ui-nav">' +
            '			<div class="left">' +
            '               <div class="item">'+
            '                   <label>用户昵称 ：</label>' +        
            '                   <input style="width:90px;" type="text" class="ui-input" onchange="{{$page}}.templateData.search.name=this.value;" value="{{search.name}}" />' +
            '               </div>'+            
            '               <div class="item">'+
            '                   <label>权限：</label>' +
            '                   <select style="width:70px" id="farm-list-region-select" class="ui-select" onchange="{{$page}}.templateData.search.power=this.value;">' +
            '                       {{each powerLabel}}' +
            '                           <option value="{{$index}}">{{$value}}</option>' +
            '                       {{/each}}' +
            '                   </select>' +
            '               </div>'+            
            '               <div class="item">'+
            '                   <label>级别：</label>' +
            '                   <select style="width:70px" id="farm-list-region-select" class="ui-select" onchange="{{$page}}.templateData.search.level=this.value;">' +
            '                       {{each levelLabel}}' +
            '                           <option value="{{$index}}">{{$value}}</option>' +
            '                       {{/each}}' +
            '                   </select>' +
            '               </div>'+                
            '               <div class="item">'+
            '                   <label>创建时间：</label>' +
            '                   <input type="text" class="ui-input page-input-date ui-form-datetime" onchange="{{$page}}.templateData.search.startAt=this.value;" value="{{search.startAt}}" />' +
            '                   <span>至</span>' +
            '                   <input type="text" class="ui-input page-input-date ui-form-datetime" onchange="{{$page}}.templateData.search.endAt=this.value;" value="{{search.endAt}}" />' +
            '               </div>'+      
            '			</div>' +
            '			<div class="right">' +
            '				<a class="ui-btn" onclick="{{$page}}.doSearch(event, this);">查询</a>' +
            '			</div>' +
            '		</div>' +
            '		{{if userList}}' +
            '		<div class="page-table ui-table ui-table-hover">' +
            '			<div class="ui-th">' +
            '				<div class="ui-td" style="width:70px;">' +
            '					<div class="ui-con">用户ID</div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:110px;">' +
            '					<div class="ui-con">昵称</div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:70px;">' +
            '					<div class="ui-con">性别</div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:80px;">' +
            '					<div class="ui-con">头像</div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:80px;">' +
            '					<div class="ui-con">身份</div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:80px;">' +
            '					<div class="ui-con">级别</div>' +
            '				</div>' +            
            '				<div class="ui-td" style="width:150px;">' +
            '					<div class="ui-con">创建时间</div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:;">' +
            '					<div class="ui-con">操作</div>' +
            '				</div>' +
            '			</div>' +
            '			{{each userList as item index}}' +
            '			<div class="ui-tr">' +
            '				<div class="ui-td" style="width:70px;">' +
            '					<div class="ui-con">' +
            '                       {{item.id}}' +
            '                   </div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:110px;">' +
            '					<div class="ui-con">' +
            '                       {{item.wxNickname}}' +
            '                   </div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:70px;">' +
            '					<div class="ui-con">{{sexLabel[item.wxSex]}}</div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:80px;">' +
            '					<div class="ui-con ui-img">' +
            '						<img src="{{item.wxAvatar}}" width="70" height="70"/>' +
            '					</div>' +
            '				</div>' +            
            '               <div class="ui-td" style="width:80px;">' +
            '                   <div class="ui-con">{{powerLabel[item.power]}}</div>' +
            '               </div>' +            
            '                <div class="ui-td" style="width:80px;">' +
            '                   <div class="ui-con">{{levelLabel[item.level]}}</div>' +
            '               </div>' +            
            '				<div class="ui-td" style="width:150px;">' +
            '                   <div class="ui-con" title="{{item.createAt | datetime}}">'+
            '                       {{if item.createAt}}'+
            '                       <p style="margin:0;">{{item.createAt | datetime}}</p>'+
            '                       {{/if}}'+
            '                   </div>' +
            '				</div>' +
            '				<div class="ui-td" style="width:;">' +
            '					<div class="ui-con ui-btns">' +
            '						<a onclick="{{$page}}.doEdit({{item.id}})">详情</a>' +
            '						{{ if item.level==3}}' +
            '						<a onclick="{{$page}}.doLevel({{item.id}}, 2, \'升级为钻石会员\')">升级为钻石会员</a>' +
            '						{{else if item.level==2}}' +
            '						<a onclick="{{$page}}.doLevel({{item.id}}, 1, \'升级为皇冠会员\')">升级为皇冠会员</a>' +
            '                       {{else if item.level==1}}' +
            '						{{/if}}' +            
            '                       {{ if item.power==2}}' +
            '                       <a onclick="{{$page}}.toFarm({{item.id}})">变为农场主</a>' +
            '                       {{/if}}' +
            '					</div>' +
            '				</div>' +
            '			</div>' +
            '			{{/each}}' +
            '			{{if userList.length==0}}' +
            '			<div class="ui-tr ui-tr-nohover">' +
            '				<div class="page-empty">没有任何数据</div>' +
            '			</div>' +
            '			{{/if}}' +
            '		</div>' +
            '       {{if userList.length > 0}}' +
            '		<div class="page-btns">' +
            '			<div class="right">{{#PAGE}}</div>' +
            '		</div>' +
            '       {{/if}}' +
            '		{{else}}' +
            '		<div class="page-loading">正在加载中...</div>' +
            '		{{/if}}' +
            '	</div>' +
            '</div>' +
            '';
    },
    load: function () {
    },
    loaded: function () {
        this.doSearch();
    }
}, {
    /**
     * 执行搜素
     */
    doSearch: function () {
        var that = this;
        that.templateData.userList = null;      
        that.templateData.search.startAt = $wk.date.unixtime(that.templateData.search.startAt);
        that.templateData.search.endAt = $wk.date.unixtime(that.templateData.search.endAt + ' 23:59:59');
        $wk.api.users.userGetList(that.templateData.search,function (result) {
            that.templateData.userList = result.data.dataList || [];
            that.templateData.TotalCount = result.data.dataCount;
            that.templateData.PAGE = $wk.pagination.eachHtml(that.id,
                that.templateData.search.pageNumber + 1,
                that.templateData.search.pageSize,
                that.templateData.TotalCount,
                5,
                function (pageIndex) {
                    that.templateData.search.pageNumber = pageIndex - 1;
                    that.doSearch();
                }
            );          
            that.templateData.search.startAt = $wk.date.date(that.templateData.search.startAt);
            that.templateData.search.endAt = $wk.date.date(that.templateData.search.endAt);
            that.drawView(that.parseTemplate(that.template, that.templateData));
        });
    },
    /**
     * 执行编辑
     * @param id
     */
    doEdit: function (id) {
        $wk.page.load('users-msg', [id]);
    },
    /**
     * 修改用户级别
     */
    doLevel: function (id, status, msg) {
        var that = this;
        $wk.msg.showConfirm('确定要将用户' + msg + '吗？', function () {
            $wk.api.users.userUpdateLevel(id, status, function () {
                that.pageLoaded();
            });
        });
    },       
    /**
     * 修改用户权限
     */ 
    doPower: function (id, farmId) {
        var that = this;
        $wk.api.users.userUpdatepower(id, farmId, function () {
            that.pageLoaded();
        });
    },  
    /**
     * 变为农场主
     */
    toFarm: function (id) {
        var that = this;
        that.templateData.addFarm = {};
        $wk.api.showLoading('正在查询农场列表...');
        $wk.api.farm.farmGetList({},function (result) {
            $wk.api.hideLoading();
            that.templateData.addFarm.allFarms = result.data.dataList;
            var str = "<h3>请选择农场</h3>"+
                      "<select id='toFarm-select'>"+
                      "{{each allFarms}}"+
                      "{{if $value.userId==0}}"+
                      "<option value='{{$value.id}}'>{{$value.title}}</option>"+
                      "{{/if}}"+
                      "{{/each}}"+
                      "</select>";
            $wk.msg.showConfirm(that.parseTemplate(str,that.templateData.addFarm),function () {
                var farmId = $('#toFarm-select').val();
                that.doPower(id, farmId, '变为农场主');
            });
        });
    }

}));

