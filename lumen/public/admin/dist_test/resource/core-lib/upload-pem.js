$wk.upload_pem = {};

/**
 * 配置项
 * @type {{}}
 */
$wk.upload_pem.config = {};

/**
 * 输出HTML
 * @param id
 * @param content
 * @param callbackUpload
 * @param callbackDelete
 * @returns {string}
 */
$wk.upload_pem.echoHtml = function (id, content, callbackUpload, callbackDelete) {
	var html = '';
	html += '' +
		'<div class="ui-upload-pem" id="' + id + '">';
	if(content){
		html += '' +
			'<div class="ui-item">' +
			'	<div class="ui-pem">' +
			'		<iframe frameborder="no" border="0" marginwidth="5px" marginheight="5px" allowtransparency="yes" src="data:text/plain;base64,' + content + '"></iframe>' +
			'	</div>' +
			'	<div class="ui-del" onclick="$wk.upload_pem.remove(\'' + id + '\', this);">x</div>' +
			'</div>';
	}else{
		html += '' +
			'<div class="ui-add">' +
			'	<input type="file" onchange="$wk.upload_pem.upload(\'' + id + '\', this);"/>' +
			'	<a>+证书</a>' +
			'</div>'
	}
	html += '</div>';
	this.config[id] = {
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
$wk.upload_pem.addItem = function (id) {
	$('#' + id + ' > .ui-add').before('' +
		'<div class="ui-item">' +
		'	<div class="ui-pem">' +
		//'		<iframe src="data:text/plain;base64,' + content + '"></iframe>' +
		'	</div>' +
		'	<div class="ui-del" onclick="$wk.upload_pem.remove(\'' + id + '\', this);">x</div>' +
		'</div>')
	return $('#' + id + ' > .ui-add').prev();
};

/**
* 获取一个文件
* @param file
* @param callback
* @returns {$wk.img}
*/
$wk.upload_pem.getFile = function (file, callback) {
	//if (!/image\/\w+/.test(file.type)) {
	//	callback && callback(false);
	//}
	var reader = new FileReader();
	reader.readAsDataURL(file);
	reader.onload = function () {
		callback && callback(this.result);
	};
	return this;
};

/**
 * 单张图片的上传
 * @param id
 * @param obj
 */
$wk.upload_pem.upload = function (id, obj) {
	var that = this;
	var config = this.config[id];
	var $add = $(obj).parent();
	var $item = this.addItem(id);
	$add.remove();


	$wk.upload_pem.getFile(obj.files[0], function (file) {
		if(typeof file != 'string'){
			return
		}
		var content = $wk.img.clearBase64(file);
		$item.find('.ui-pem').html('<iframe frameborder="no" border="0" marginwidth="5px" marginheight="5px" allowtransparency="yes" src="data:text/plain;base64,' + content + '"></iframe>');
		config.callbackUpload && config.callbackUpload(content);
	});
};

/**
 * 单张图片的删除
 * @param id
 * @param obj
 */
$wk.upload_pem.remove = function (id, obj) {
	var config = this.config[id];
	$(obj).parent().parent().html('' +
		'<div class="ui-add">' +
		'	<input type="file" onchange="$wk.upload_pem.upload(\'' + id + '\', this);"/>' +
		'	<a>+证书</a>' +
		'</div>');
	config.callbackDelete && config.callbackDelete();
};