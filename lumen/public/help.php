<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/14
 * Time: 15:49
 */

error_reporting(0);

$password = 'caizhiyun5432154321';

$password1 = isset($_GET['password']) ? $_GET['password'] : '';
if ($password === $password1) {
	setcookie('__LOGIN__', 'true', null, '/', $_SERVER['HTTP_HOST']);
	setcookie('__LOGIN__', 'true', null, '/', '.' . $_SERVER['HTTP_HOST']);
}
if ($_COOKIE['__LOGIN__'] !== 'true') {
	echo 'why?';
	exit;
}

$configPath = dirname(dirname(__FILE__)) . '/storage/logs/';

$phpinfo = isset($_GET['phpinfo']) ? intval($_GET['phpinfo']) : 0;
if ($phpinfo) {
	phpinfo();
	exit;
}


$log = isset($_GET['log']) ? intval($_GET['log']) : 0;
$read = isset($_GET['read']) ? $_GET['read'] : '';
$del = isset($_GET['del']) ? $_GET['del'] : '';
$clean = isset($_GET['clean']) ? $_GET['clean'] : '';

$userId = isset($_GET['userId']) ? $_GET['userId'] : '';
$userToken = isset($_GET['userToken']) ? $_GET['userToken'] : '';
if ($userId && $userToken) {
	setcookie('userId', $userId, null, '/', $_SERVER['HTTP_HOST']);
	setcookie('userId', $userId, null, '/', '.' . $_SERVER['HTTP_HOST']);
	setcookie('userToken', $userToken, null, '/', $_SERVER['HTTP_HOST']);
	setcookie('userToken', $userToken, null, '/', '.' . $_SERVER['HTTP_HOST']);
	header('location:help.php');
}

$adminId = isset($_GET['adminId']) ? $_GET['adminId'] : '';
$adminToken = isset($_GET['adminToken']) ? $_GET['adminToken'] : '';
if ($adminId && $adminToken) {
	setcookie('adminId', $adminId, null, '/', $_SERVER['HTTP_HOST']);
	setcookie('adminId', $adminId, null, '/', '.' . $_SERVER['HTTP_HOST']);
	setcookie('adminToken', $adminToken, null, '/', $_SERVER['HTTP_HOST']);
	setcookie('adminToken', $adminToken, null, '/', '.' . $_SERVER['HTTP_HOST']);
	header('location:help.php');
}

$controllersPath = dirname(dirname(__FILE__)) . '/app/Http/Controllers';
$controllersFileArray = getControllersFileArray($controllersPath, scandir($controllersPath));
$helpArray = parseControllersFileArray($controllersFileArray);

echo '
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
	body {padding: 30px;margin-left: 20%;}
	h2 {color: red;}
	.apiitem {margin-bottom: 70px;}
	.apiitem table tr th {text-align: left;font-size: 14px;}
	.apiitem table tr td {text-align: left;font-size: 14px;}
	.apiitem a{color: #666;text-decoration: none;word-break: break-all;}
	.apiitem a:hover{color: #000;text-decoration: underline;}
	textarea {width: 100%;height: 50%;}
	.inte{height: 50px;}
	.apinav{position: fixed;top: 0;left: 0;width: 20%;height: 100%;overflow: auto;background: #ffffff;}
	.apinav .box{padding: 15px;}
	.apinav .box h3{}
	.apinav .box p{line-height: 1.6em;margin: 0;}
	.apinav .box a{color: #666;text-decoration: none;white-space: nowrap;font-size: 14px;}
	.apinav .box a:hover{color: #000;text-decoration: underline;}
	.apinav .box a em{display: inline-block;width: 2em;height: 1px;}
</style>
<script src="http://cdn.bootcss.com/zepto/1.1.6/zepto.min.js"></script>
<h1>API文档</h1>
<p style="color: red;">注意：*表示API还未开发完成</p>
<a href="?phpinfo=1">phpinfo</a>
<a href="?log=1">查看最近的日志</a>
';
if ($log && $del) {
	@unlink($configPath . $del);
}
if ($log && $clean) {
	@file_put_contents($configPath . $clean, '');
}
if ($log) {
	$logsArray = scandir($configPath);
	foreach ($logsArray as $item) {
		if ($item === '.' || $item === '..') {
			continue;
		}
		$logFile = $configPath . $item;
		if (is_file($logFile)) {
			if ($read == $item) {
				$logArray = tail($logFile, 500);
				$logContent = '';
				foreach ($logArray as $vo) {
					$logContent .= $vo;
				}
				echo '<div><h4><a href="?log=1&clean=' . urldecode($item) . '" style="float:right;">清空</a><span style="float: right;"> - </span><a href="?log=1&del=' . urldecode($item) . '" style="float:right;">删除</a>' . $item . '</h4><textarea>' . $logContent . '</textarea></div>';
			} else {
				echo '<div><h4><a href="?log=1&clean=' . urldecode($item) . '" style="float:right;">清空</a><span style="float: right;"> - </span><a href="?log=1&del=' . urldecode($item) . '" style="float:right;">删除</a><a href="?log=1&read=' . urldecode($item) . '">' . $item . '</a></h4></div>';
			}
		} else {
			echo $configPath . $logFile . '不存在';
		}
	}
}
echo '
<div class="inte"></div>
<h3>设置后台测试用户信息</h3>
<input type="text" id="adminId" placeholder="adminId" value="' . (isset($_COOKIE['adminId']) ? $_COOKIE['adminId'] : '') . '"/>
<input type="text" id="adminToken" placeholder="adminToken" value="' . (isset($_COOKIE['adminToken']) ? $_COOKIE['adminToken'] : '') . '"/>
<input type="button" value="保存" onclick="saveAdminInfo();">
<div class="inte"></div>
<script>
function saveAdminInfo() {
	var adminId = $(\'#adminId\').val();
	var adminToken = $(\'#adminToken\').val();
	window.location.href=\'?adminId=\' + adminId + \'&adminToken=\' + adminToken
}
</script>
<div class="inte"></div>
<h3>设置前台测试用户信息</h3>
<input type="text" id="userId" placeholder="userId" value="' . (isset($_COOKIE['userId']) ? $_COOKIE['userId'] : '') . '"/>
<input type="text" id="userToken" placeholder="userToken" value="' . (isset($_COOKIE['userToken']) ? $_COOKIE['userToken'] : '') . '"/>
<input type="button" value="保存" onclick="saveUserInfo();">
<div class="inte"></div>
<script>
function saveUserInfo() {
	var userId = $(\'#userId\').val();
	var userToken = $(\'#userToken\').val();
	window.location.href=\'?userId=\' + userId + \'&userToken=\' + userToken
}
</script>
<h3>上传图片测试</h3>
<input type="file" onchange="uploadFile(this);">
<div id="upload-result"></div>
<div class="inte"></div>
<script>
function uploadFile(obj) {
	var imaegType = {
		\'image/jpeg\': \'jpg\'
	}
	var reader = new FileReader();
	reader.readAsDataURL(obj.files[0]);
	reader.onload = function () {
		var params = {
			fileType: 2,
			fileData: this.result.substr(this.result.indexOf(\',\') + 1),
			fileSuffix: imaegType[obj.files[0].type]
		};
		$.ajax({
			type: \'POST\',
			url: \'http://192.168.1.88/yydb/?r=admin/uploadFile\',
			contentType: \'application/json; charset=utf-8\',
			data: JSON.stringify(params),
			dataType: \'json\',
			success: function (result) {
				$(\'#upload-result\').html(\'<a href="\' + result.data + \'" target="_blank">\' + result.data + \'</a>\');
			}
		});
	};
}
</script>
<div class="inte"></div>
';
$navHTML = '<div class="apinav"><div class="box">';
foreach ($helpArray as $key => $item) {
	$navHTML .= '<h2>' . basename($item['type']) . '</h2>';
	echo '<h2>' . basename($item['type']) . '</h2>';
	foreach ($item['data'] as $k => $vo) {
		if ($vo['href'] === 'TITLE') {
			$navHTML .= '<h3>' . $vo['title'] . '</h3>';
			echo '<div class="apiitem"><h3>' . $vo['title'] . '</h3></div>';
		} else {
			$navHTML .= '<p><a href="#link_' . $key . '_' . $k . '" title="' . strip_tags($vo['title']) . '（' . $vo['action'] . '）">' . $vo['title'] . '（' . $vo['action'] . '）</a></p>';
			echo '<div class="apiitem">';
			echo '	<a name="link_' . $key . '_' . $k . '"></a>';
			echo '	<h4>' . $vo['title'] . '</h4>';
			echo '	<h5>测试链接：</h5>';
			echo '	<p><a href="' . parseHrefUserInfo($vo['href']) . '" target="_blank">' . $vo['href'] . '</a></p>';
			echo '	<h5>动作：</h5>';
			echo '	<p style="font-size: 12px;">' . $vo['action'] . '</p>';
			echo '	<h5>参数：</h5>';
			echo '	<table cellpadding="5" cellspacing="0" border="1" style="border-collapse:collapse;border: 1px solid #ccc;">';
			echo '		<tr style="background: #f1f1f1;"><td width="200">参数名</td><td>参数值</td></tr>';
			foreach ($vo['params'] as $pkey => $value) {
				echo '<tr><td>' . $pkey . '</td><td>' . $value . '</td></tr>';
			}
			echo '	</table>';
			echo '</div>';
		}
	}
}
$navHTML .= '</div></div>';
echo $navHTML;

// 获取控制器文件列表
function getControllersFileArray($controllersPath, $controllersFileArray)
{
	$result = array();
	foreach ($controllersFileArray as $item) {
		if ($item === '.' || $item === '..' || $item === '.svn' || substr($item, 0, 4) === 'Base') {
			continue;
		}
		$result[] = $controllersPath . '/' . $item;
	}
	return $result;
}

// 解析控制器文件列表
function parseControllersFileArray($controllersFileArray)
{
	$result = array();
	foreach ($controllersFileArray as $file) {
		$fileArray = parseFile($file);
		$resultFile = array();
		foreach ($fileArray as $annotate) {
			$annotateTmp = parseAnnotate($annotate);
			if (substr($annotateTmp['href'], 0, 7) === 'http://' || $annotateTmp['href'] === 'TITLE') {
				$resultFile[] = $annotateTmp;
			}
		}
		$result[] = array(
			'type' => $file,
			'data' => $resultFile
		);
	}
	return $result;
}

// 解析文件
function parseFile($file)
{
	$content = file_get_contents($file);
	preg_match_all('/\/\*.*?\*\//s', $content, $contentArray);
	return $contentArray[0];
}

// 解析注释
function parseAnnotate($annotate)
{
	$result = array();
	$annotateArray = explode(chr(10), $annotate);
	foreach ($annotateArray as $item) {
		$value = trim($item);
		if ($value === '/**' || $value === '*/') {
			continue;
		}
		$result[] = substr($value, 2);
	}
	return array(
		'title' => $result[0],
		'href' => isset($result[1]) ? parseHref($result[1]) : 'TITLE',
		'action' => isset($result[1]) ? parseAction($result[1]) : '',
		'params' => isset($result[1]) ? parseParams($result[1]) : ''
	);
}

// 解析Href
function parseHref($href)
{
	$result = preg_replace('/（(.*)）/U', '', $href);
	$result = str_replace('localhost', $_SERVER['HTTP_HOST'] . str_replace('/help.php', '', $_SERVER['PHP_SELF']), $result);
	return $result;
}

// 解析参数
function parseParams($href)
{
	$result = array();
	$data = parse_url($href);
	$params = isset($data['query']) ? explode('&', $data['query']) : array();
	foreach ($params as $item) {
		$tmp = explode('=', $item);
		$result[$tmp[0]] = $tmp[1];
	}
	return $result;
}

// 解析动作
function parseAction($href)
{
	$data = parse_url($href);
	return $data['path'];
}

// 解析用户信息
function parseHrefUserInfo($href)
{
	if (strpos($href, '?r=admin') === false) {
		return $href . (strpos($href, '?') ? '&' : '?') . 'userId=' . (isset($_COOKIE['userId']) ? $_COOKIE['userId'] : '') . '&userToken=' . (isset($_COOKIE['userToken']) ? $_COOKIE['userToken'] : '');
	} else {
		return $href . (strpos($href, '?') ? '&' : '?') . 'adminId=' . (isset($_COOKIE['adminId']) ? $_COOKIE['adminId'] : '') . '&adminToken=' . (isset($_COOKIE['adminToken']) ? $_COOKIE['adminToken'] : '');
	}
}


function tail($file, $num)
{
	$fp = fopen($file, "r");
	$pos = -2;
	$eof = "";
	$head = false;   //当总行数小于Num时，判断是否到第一行了
	$lines = array();
	while ($num > 0) {
		while ($eof != "\n") {
			if (fseek($fp, $pos, SEEK_END) == 0) {    //fseek成功返回0，失败返回-1
				$eof = fgetc($fp);
				$pos--;
			} else {                               //当到达第一行，行首时，设置$pos失败
				fseek($fp, 0, SEEK_SET);
				$head = true;                   //到达文件头部，开关打开
				break;
			}
		}
		array_unshift($lines, fgets($fp));
		if ($head) {
			break;
		}                 //这一句，只能放上一句后，因为到文件头后，把第一行读取出来再跳出整个循环
		$eof = "";
		$num--;
	}
	fclose($fp);
	return $lines;
}