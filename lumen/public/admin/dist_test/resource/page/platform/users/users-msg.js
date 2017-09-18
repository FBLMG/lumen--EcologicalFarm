/**
 * 用户详情
 */
$wk.page.createPage('users-msg', new $wk.page.newPage({
    init: function () {
        this.templateData = {
            powerLabel: ['', '农场主', '会员'],
            levelLabel: ['', '皇冠会员', '钻石会员', '星级会员'],
            sexLabel: ['未知','男','女','未知']
        };
        this.template = '' +
            '<div class="page-view">' +
            '   <div class="page-right">' +
            '       <div class="form ui-form2">' +
            //详情
            '           <div class="box">' +            
            '           <div class="page-table ui-table ui-table-hover">' +
            '               <h3 class="l" style="padding:0 13px;">用户详细信息</h3>' +
            '               <div class="ui-th">' +
            '                   <div class="ui-td" style="width:70px;">' +
            '                       <div class="ui-con">用户ID</div>' +
            '                   </div>' +
            '                   <div class="ui-td" style="width:110px;">' +
            '                       <div class="ui-con">昵称</div>' +
            '                   </div>' +
            '                   <div class="ui-td" style="width:70px;">' +
            '                       <div class="ui-con">性别</div>' +
            '                   </div>' +
            '                   <div class="ui-td" style="width:80px;">' +
            '                       <div class="ui-con">头像</div>' +
            '                   </div>' +
            '                   <div class="ui-td" style="width:80px;">' +
            '                       <div class="ui-con">身份</div>' +
            '                   </div>' +   
            '                   {{if userMsg.farmName}}'+       
            '                   <div class="ui-td" style="width:80px;">' +
            '                       <div class="ui-con">农场名称</div>' +
            '                   </div>' +
            '                   {{/if}}'+
            '                   <div class="ui-td" style="width:80px;">' +
            '                       <div class="ui-con">级别</div>' +
            '                   </div>' +     

            '                   <div class="ui-td" style="width:150px;">' +
            '                       <div class="ui-con">创建时间</div>' +
            '                   </div>' +
            '                   <div class="ui-td" style="width:150px;">' +
            '                       <div class="ui-con">更新时间</div>' +
            '                   </div>' +          
            '                   </div>' +       
            '               <div class="ui-tr">' +          
            '                   <div class="ui-td" style="width:70px;">' +
            '                   <div class="ui-con">' +
            '                       {{userMsg.id}}' +
            '                   </div>' +
            '               </div>' +
            '               <div class="ui-td" style="width:110px;">' +
            '                   <div class="ui-con">' +
            '                       {{userMsg.wxNickname}}' +
            '                   </div>' +
            '               </div>' +
            '               <div class="ui-td" style="width:70px;">' +
            '                   <div class="ui-con">{{sexLabel[userMsg.wxSex]}}</div>' +
            '               </div>' +
            '               <div class="ui-td" style="width:80px;">' +
            '                   <div class="ui-con ui-img">' +
            '                       <img src="{{userMsg.wxAvatar}}" width="70" height="70"/>' +
            '                   </div>' +
            '               </div>' +            
            '               <div class="ui-td" style="width:80px;">' +
            '                   <div class="ui-con">{{powerLabel[userMsg.power]}}</div>' +
            '               </div>' +                     
            '                   {{if userMsg.farmName}}'+       
            '                   <div class="ui-td" style="width:80px;">' +
            '                       <div class="ui-con">{{userMsg.farmName}}</div>' +
            '                   </div>' +
            '                   {{/if}}'+   
            '                <div class="ui-td" style="width:80px;">' +
            '                   <div class="ui-con">{{levelLabel[userMsg.level]}}</div>' +
            '               </div>' +            
            '               <div class="ui-td" style="width:150px;">' +
            '                   <div class="ui-con" title="{{userMsg.createAt | datetime}}">'+
            '                       {{if userMsg.createAt}}'+
            '                       <p style="margin:0;">{{userMsg.createAt | datetime}}</p>'+
            '                       {{/if}}'+
            '                   </div>' +
            '               </div>' +                 
            '               <div class="ui-td" style="width:150px;">' +
            '                   <div class="ui-con" title="{{userMsg.updateAt | datetime}}">'+
            '                       {{if userMsg.updateAt}}'+
            '                       <p style="margin:0;">{{userMsg.updateAt | datetime}}</p>'+
            '                       {{/if}}'+
            '                   </div>' +
            '               </div>' +   
            '               </div>' +   
            '           </div>' +           
            '           </div>' +

            '           </div>' +
            '       </div>' +
            '   </div>' +
            '</div>' +
            '';
    },
    load: function () {
        this.templateData.userMsg = {};
        this.templateData.userMsgId = parseInt(this.params[0] || 0);
    },
    loaded: function () {
        var that = this;
        $wk.api.showLoading();
        if (that.templateData.userMsgId) {
            $wk.api.users.userGet(that.templateData.userMsgId, function (result) {
                that.templateData.userMsg = result.data;
                that.drawView(that.parseTemplate(that.template, that.templateData));
                $wk.api.hideLoading();
            });
        }

    }
}, {

}));