/**
 * 产品溯源详情
 */
$wk.page.createPage('product-root-save', new $wk.page.newPage({
	init: function () {
		this.templateData = {
			areaSearch: {
				name: '',
				startAt: 0,
				endAt: 0,
				pageSize: 0,
				pageNumber: 0
			},
			statusLabel: ['','肉质类','果蔬类']
		};
		this.template = '' +
			'<div class="page-view">' +
			'	<div class="page-right">' +
			'		<div class="form ui-form2">' +
			// 基本信息
			'			<div class="box">' +			
			'			<div class="page-table ui-table ui-table-hover">' +
			'				<h3 class="l" style="padding:0 13px;">基本信息</h3>' +
			'				<div class="ui-th">' +
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">农场主</div>' +
			'					</div>' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">农场名称</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">档案名称</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">企业信息</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">产品名称</div>' +
			'					</div>' +				
			'				</div>' +			
			'				<div class="ui-tr">' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.userName}}</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.farmName}}</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.fileName}}</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.enterpriseInformation}}</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.productName}}</div>' +
			'					</div>' +
			'				</div>' +				
			'				<div class="ui-th">' +
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">地区</div>' +
			'					</div>' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">县级</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">生长产地</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">饲养数量</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">产品类型</div>' +
			'					</div>' +				
			'				</div>' +			
			'				<div class="ui-tr">' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.reginName}}</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.countyName}}</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.growingArea}}</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.feedingQuantity}}</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{statusLabel[productRoot.productCategory]}}</div>' +
			'					</div>' +
			'				</div>' +				
			'				<div class="ui-th">' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">保质期限</div>' +
			'					</div>' +	
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">登记时间</div>' +
			'					</div>' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">认证信息</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">创建时间</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">更新时间</div>' +
			'					</div>' +				
			
			'				</div>' +			
			'				<div class="ui-tr">' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.shelfLife}}</div>' +
			'					</div>' +
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.registrationTime}}</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">{{productRoot.authenticationInformation}}</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{productRoot.createAt | datetime}}">'+
			'                   		{{if productRoot.createAt}}'+
			'							<p style="margin:0;">{{productRoot.createAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{productRoot.updateAt | datetime}}">'+
			'                   		{{if productRoot.updateAt}}'+
			'							<p style="margin:0;">{{productRoot.updateAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +			
			'				</div>' +			
			'			</div>' +			
			'			</div>' +	
			 //生长情况
			'			<div class="box">' +			
			'			<div class="page-table ui-table ui-table-hover">' +
			'				<h3 class="l" style="padding:0 13px;">生长情况</h3>' +
			'				<div class="ui-th">' +
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">生长记录</div>' +
			'					</div>' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">图片</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">视频</div>' +
			'					</div>' +							
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">创建时间</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">更新时间</div>' +
			'					</div>' +	
			'				</div>' +		
			'               {{each productRoot.growthStatus}}'+	
			'				<div class="ui-tr">' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con"  title="{{$value.content}}">{{$value.content}}</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con ui-img">' +
			'                           {{each $value.images}}'+
			'							<a target="_blank" href="'+CONFIG_DOMAIN_UPLOAD+'{{$value}}"><img src="'+CONFIG_DOMAIN_UPLOAD+'{{$value | image:[56, 56]}}"/></a>' +
			'                           {{/each}}'+
			'						</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.video}}" style="word-break: break-all;"><a href="{{$value.video}}" target="_blank">{{$value.video}}</a></div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.createAt | datetime}}">'+
			'                   		{{if $value.createAt}}'+
			'							<p style="margin:0;">{{$value.createAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.updateAt | datetime}}">'+
			'                   		{{if $value.updateAt}}'+
			'							<p style="margin:0;">{{$value.updateAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +			
			'				</div>' +	
			'               {{/each}}'+			
			'			</div>' +			
			'			</div>' +			 
			//饲养情况
			'			<div class="box">' +			
			'			<div class="page-table ui-table ui-table-hover">' +
			'				<h3 class="l" style="padding:0 13px;">饲养情况</h3>' +
			'				<div class="ui-th">' +
			'					<div class="ui-td" style="width: 480px;">' +
			'						<div class="ui-con">饲养记录</div>' +
			'					</div>' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">创建时间</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">更新时间</div>' +
			'					</div>' +			
			'				</div>' +		
			'               {{each productRoot.feedStatus}}'+	
			'				<div class="ui-tr">' +			
			'					<div class="ui-td" style="width: 480px;">' +
			'						<div class="ui-con"  title="{{$value.content}}">{{$value.content}}</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.createAt | datetime}}">'+
			'                   		{{if $value.createAt}}'+
			'							<p style="margin:0;">{{$value.createAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.updateAt | datetime}}">'+
			'                   		{{if $value.updateAt}}'+
			'							<p style="margin:0;">{{$value.updateAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +		
			'				</div>' +	
			'               {{/each}}'+			
			'			</div>' +			
			'			</div>' +			
			//疫苗注射情况
			'			<div class="box">' +			
			'			<div class="page-table ui-table ui-table-hover">' +
			'				<h3 class="l" style="padding:0 13px;">疫苗注射情况</h3>' +
			'				<div class="ui-th">' +
			'					<div class="ui-td" style="width: 480px;">' +
			'						<div class="ui-con">注射记录</div>' +
			'					</div>' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">创建时间</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">更新时间</div>' +
			'					</div>' +			
			'				</div>' +		
			'               {{each productRoot.vaccineStatus}}'+	
			'				<div class="ui-tr">' +			
			'					<div class="ui-td" style="width: 480px;">' +
			'						<div class="ui-con"  title="{{$value.content}}">{{$value.content}}</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.createAt | datetime}}">'+
			'                   		{{if $value.createAt}}'+
			'							<p style="margin:0;">{{$value.createAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.updateAt | datetime}}">'+
			'                   		{{if $value.updateAt}}'+
			'							<p style="margin:0;">{{$value.updateAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +		
			'				</div>' +	
			'               {{/each}}'+			
			'			</div>' +			
			'			</div>' +			
			//流通情况
			'			<div class="box">' +			
			'			<div class="page-table ui-table ui-table-hover">' +
			'				<h3 class="l" style="padding:0 13px;">流通情况</h3>' +
			'				<div class="ui-th">' +
			'					<div class="ui-td" style="width: 480px;">' +
			'						<div class="ui-con">流通记录</div>' +
			'					</div>' +						
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">创建时间</div>' +
			'					</div>' +			
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con">更新时间</div>' +
			'					</div>' +			
			'				</div>' +		
			'               {{each productRoot.circulationRecord}}'+	
			'				<div class="ui-tr">' +			
			'					<div class="ui-td" style="width: 480px;">' +
			'						<div class="ui-con"  title="{{$value.content}}">{{$value.content}}</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.createAt | datetime}}">'+
			'                   		{{if $value.createAt}}'+
			'							<p style="margin:0;">{{$value.createAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +				
			'					<div class="ui-td" style="width: 160px;">' +
			'						<div class="ui-con" title="{{$value.updateAt | datetime}}">'+
			'                   		{{if $value.updateAt}}'+
			'							<p style="margin:0;">{{$value.updateAt | datetime}}</p>'+
			'                   		{{/if}}'+
			'						</div>' +
			'					</div>' +		
			'				</div>' +	
			'               {{/each}}'+			
			'			</div>' +			
			'			</div>' +

			'			</div>' +
			'		</div>' +
			'	</div>' +
			'</div>' +
			'';
	},
	load: function () {
		this.templateData.productRoot = {};
		this.templateData.productRootId = parseInt(this.params[0] || 0);
	},
	loaded: function () {
		var that = this;
		$wk.api.showLoading();
		if (that.templateData.productRootId) {
			$wk.api.productRoot.productTraceabilityGet(that.templateData.productRootId, function (result) {
				that.templateData.productRoot = result.data;
				console.log(that.templateData.productRoot)
				that.drawView(that.parseTemplate(that.template, that.templateData));
				$wk.api.hideLoading();
			});
		}

	}
}, {

}));