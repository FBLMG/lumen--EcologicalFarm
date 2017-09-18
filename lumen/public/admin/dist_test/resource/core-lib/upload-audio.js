/**
 * Created by toby on 16/5/16.
 */
$wk.upload_audio = {};

/**
 * 音乐上传的api
 * @type {*}
 */
$wk.upload_audio.api = $wk.api.api('uploadFile');

/**
 * 配置项
 * @type {{}}
 */
$wk.upload_audio.config = {};

/**
 * 截取类型
 * @type {number}
 * 1：大图 2：中图 3：小图
 */
$wk.upload_audio.serviceType = {
	BIG: 1,
	MIDDLE: 2,
	SMALL: 3,
	SONG: 4
};

/**
 * 默认显示格式
 * @type {*[]}
 */
$wk.upload_audio.defalutFormat = [[], [640, 640], [128, 128], [56, 56]];

/**
 * 输出HTML
 * @param id
 * @param audios
 * @param multiline
 * @param serviceType
 * @param callbackUpload
 * @param callbackDelete
 * @returns {string}
 */
$wk.upload_audio.echoHtml = function (id, audios, multiline, serviceType, callbackUpload, callbackDelete) {
	var html = '';
	var that = this;
	html += '<div class="ui-upload-audio" id="' + id + '">';
	if ($.isArray(audios)) {
		for (var a in audios) {
			html += '' +
				'<div class="ui-item">' +
				'	<div class="ui-img">' +
				'		' + (audios[a] ? '<audio controls="controls" src="' + audios[a] + '"></audio>' : '') +
				'	</div>' +
				'	<div class="ui-del" onclick="$wk.upload_audio.remove(\'' + id + '\', this);">x</div>' +
				'</div>' +
				'';
		}
	}
	html += '	<div class="ui-add"' + (multiline === false && audios.length ? ' style="display:none;"' : '') + '>';
	html += '		<input type="file" onchange="$wk.upload_audio.upload(\'' + id + '\', this);"/>';
	html += '		<a>+录音</a>';
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
$wk.upload_audio.addItem = function (id) {
	$('#' + id + ' > .ui-add').before('' +
		'<div class="ui-item">' +
		'	<div class="ui-img"></div>' +
		'	<div class="ui-del" onclick="$wk.upload_audio.remove(\'' + id + '\', this);">x</div>' +
		'</div>' +
		'');
	return $('#' + id + ' > .ui-add').prev();
};

/**
 * 单张音乐的上传
 * @param id
 * @param obj
 */
$wk.upload_audio.upload = function (id, obj) {
	var that = this;
	var config = this.config[id];
	var $add = $(obj).parent();
	var $item = this.addItem(id);
	if (config.multiline === false) { // 单项操作时，隐藏上传按钮
		$add.hide();
	}
	$wk.aud.getFile(obj.files[0], function (file) {
		$wk.upload.file($wk.upload_audio.api, {
			fileData: $wk.aud.clearBase64(file),
			fileSuffix: $wk.upload_audio.getImageType(file),
			fileType: config.serviceType,
			adminId: parseInt($wk.api.getUserId()),
			adminToken: $wk.api.getUserToken()
		}, {
			progress: function (progress) {
				$item.find('.ui-img').html('<span>' + progress + '%</span>');
			},
			success: function (result) {
				$item.find('.ui-img').html('<audio controls="controls" src="' + result.data + '"></audio>');
				config.callbackUpload && config.callbackUpload(result.data);
			}
		});
	});
};

/**
 * 单张音乐的删除
 * @param id
 * @param obj
 */
$wk.upload_audio.remove = function (id, obj) {
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


$wk.upload_audio.getImageType = function(data){
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


//$wk.upload_audio.formatImage = function(imgUrl, type){
//	var img1 = imgUrl.substring(0, imgUrl.lastIndexOf('.'));
//	var img2 = imgUrl.substring(imgUrl.lastIndexOf('.'));
//
//
//	var width = type[0];
//	var height = type[1];
//	return img1 + '_' + width + 'x' + height + img2;
//}