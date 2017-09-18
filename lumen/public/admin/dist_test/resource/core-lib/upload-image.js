/**
 * Created by toby on 16/5/16.
 */
$wk.upload_image = {};

/**
 * 图片上传的api
 * @type {*}
 */
$wk.upload_image.api = $wk.api.api('uploadFile');

/**
 * 配置项
 * @type {{}}
 */
$wk.upload_image.config = {};

/**
 * 截取类型
 * @type {number}
 * 1：大图 2：中图 3：小图
 */
$wk.upload_image.serviceType = {
	BIG: 1,
	MIDDLE: 2,
	SMALL: 3,
	SONG: 4
};

/**
 * 默认显示格式
 * @type {*[]}
 */
$wk.upload_image.defalutFormat = [[56, 56], [640, 640], [128, 128], [56, 56], [56, 56]];

/**
 * 输出HTML
 * @param id
 * @param images
 * @param multiline
 * @param serviceType
 * @param callbackUpload
 * @param callbackDelete
 * @returns {string}
 */
$wk.upload_image.echoHtml = function (id, images, multiline, serviceType, callbackUpload, callbackDelete) {
	var html = '';
	var that = this;
	html += '<div class="ui-upload-image" id="' + id + '">';
	if ($.isArray(images)) {
		for (var a in images) {
			html += '' +
				'<div class="ui-item">' +
				'	<div class="ui-img">' +
				'		' + (images[a] ? '<img src="' + CONFIG_DOMAIN_UPLOAD + $wk.upload_image.formatImage(images[a], that.defalutFormat[serviceType]) + '"/>' : '') +
				'	</div>' +
				'	<div class="ui-del" onclick="$wk.upload_image.remove(\'' + id + '\', this);">x</div>' +
				'</div>' +
				'';
		}
	}
	html += '	<div class="ui-add"' + (multiline === false && images.length ? ' style="display:none;"' : '') + '>';
	html += '		<input type="file" onchange="$wk.upload_image.upload(\'' + id + '\', this);"/>';
	html += '		<a>+图片</a>';
	html += '	</div>';
	html += '</div>';
	this.config[id] = {
		multiline: multiline || false, // 多项操作
		serviceType: serviceType || 0, // 上传尺寸
		callbackUpload: callbackUpload || function () {
		}, // 上传成功回调
		callbackDelete: callbackDelete || function () {
		} // 删除成功回调
	};
	return html;
};

/**
 * 添加项
 * @param id
 * @returns {*|jQuery}
 */
$wk.upload_image.addItem = function (id) {
	$('#' + id + ' > .ui-add').before('' +
		'<div class="ui-item">' +
		'	<div class="ui-img"></div>' +
		'	<div class="ui-del" onclick="$wk.upload_image.remove(\'' + id + '\', this);">x</div>' +
		'</div>' +
		'');
	return $('#' + id + ' > .ui-add').prev();
};

/**
 * 单张图片的上传
 * @param id
 * @param obj
 */
$wk.upload_image.upload = function (id, obj) {
	var that = this;
	var config = this.config[id];
	var $add = $(obj).parent();
	var $item = this.addItem(id);
	if (config.multiline === false) { // 单项操作时，隐藏上传按钮
		$add.hide();
	}
	$wk.img.getFile(obj.files[0], function (file) {
		$wk.upload.file($wk.upload_image.api, {
			fileData: $wk.img.clearBase64(file),
			fileSuffix: $wk.upload_image.getImageType(file),
			fileType: config.serviceType,
			adminId: parseInt($wk.api.getUserId()),
			adminToken: $wk.api.getUserToken()
		}, {
			progress: function (progress) {
				$item.find('.ui-img').html('<span>' + progress + '%</span>');
			},
			success: function (result) {
				$item.find('.ui-img').html('<img src="' + CONFIG_DOMAIN_UPLOAD + $wk.upload_image.formatImage(result.data, that.defalutFormat[config.serviceType]) + '"/>');
				config.callbackUpload && config.callbackUpload(result.data);
			}
		});
	});
};

/**
 * 单张图片的删除
 * @param id
 * @param obj
 */
$wk.upload_image.remove = function (id, obj) {
	var config = this.config[id];
	var index = 0;
	var $item = $(obj).parent();
	var $add = $('#' + id + ' > .ui-add');
	$('#' + id + ' > .ui-item').each(function ($index) {
		if ($item.get(0) === this) {
			index = $index;
		}
	});
	if (config.multiline === false) { // 单项操作时，隐藏上传按钮
		$item.hide();
		$add.show();
	} else {
		$item.remove();
	}
	config.callbackDelete && config.callbackDelete(index);
};


$wk.upload_image.getImageType = function(data){
	var type = data.substring(data.indexOf('/')+1, data.indexOf(';'));
	var types = [
		{title: 'jpeg', value:'jpg'},
		{title: 'jpg', value:'jpg'},
		{title: 'png', value:'png'},
		{title: 'gif', value:'gif'},
		{title: 'mp3', value:'mp3'},
	]
	for(var i in types){
		if(types[i].title == type){
			type = types[i].value;
			break;
		}
	}
	return type;
}


$wk.upload_image.formatImage = function(imgUrl, type){
	var img1 = imgUrl.substring(0, imgUrl.lastIndexOf('.'));
	var img2 = imgUrl.substring(imgUrl.lastIndexOf('.'));


	var width = type[0];
	var height = type[1];
	return img1 + '_' + width + 'x' + height + img2;
}