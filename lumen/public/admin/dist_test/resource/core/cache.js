/**
 * 缓存
 * @type {{}}
 */
$wk.cache = {};

/**
 * indexedDB对象名称
 * 用于检查浏览器是否支持indexedDB数据库（WP支持）
 * @type {string}
 * @private
 */
$wk.cache.__indexedDB = 'indexedDB';

/**
 * openDatabase对象名称
 * 用于检查浏览器是否支持openDatabase数据库（IOS/Android支持）
 * @type {string}
 * @private
 */
$wk.cache.__openDatabase = 'openDatabase';

/**
 * 数据库名称
 * @type {string}
 * @private
 */
$wk.cache.__datebase = 'test';

/**
 * 表名称
 * @type {string}
 * @private
 */
$wk.cache.__table = 'test';

/**
 * 打开数据库
 * @param callback function 成功后的回调方法
 * @private
 */
$wk.cache.__openDB = function (callback) {
	try {
		var that = this;
		if (that.__indexedDB in window) {
			var openRequest = indexedDB.open(that.__datebase, 1);
			openRequest.onupgradeneeded = function (e) {
				$wk.log('indexedDB onupgradeneeded');
				if (e.target.result.objectStoreNames.contains(that.__table) === false) {
					e.target.result.createObjectStore(that.__table);
				}
			};
			openRequest.onsuccess = function (e) {
				$wk.log('indexedDB onsuccess');
				callback && callback(e.target.result);
			};
			openRequest.onerror = function () {
				$wk.log('indexedDB onerror');
			};
			openRequest.onblocked = function () {
				$wk.log('onerror onblocked');
			};
		} else if (that.__openDatabase in window) {
			var db = openDatabase(that.__datebase, '', '', 5 * 1024 * 1024);
			if (!db) {
				$wk.msg.showAlert('缓存开启失败');
			}
			db.transaction(function (tx) {
				tx.executeSql('CREATE TABLE IF NOT EXISTS ' + that.__table + ' (name, value, expire)');
			});
			callback && callback(db);
		} else {
			$wk.msg.showAlert('您的浏览器不支持本地缓存技术');
		}
	} catch (e) {
		$wk.msg.showAlert('您的浏览器不支持本地缓存技术');
	}
};

/**
 * 获取缓存
 * @param name string 缓存名称
 * @param callback function 回调方法
 * @returns {$wk.cache}
 */
$wk.cache.get = function (name, callback) {
	try {
		var that = this;
		var time = parseInt(new Date().getTime() / 1000);
		if (that.__indexedDB in window) {
			that.__openDB(function (db) {
				var transaction = db.transaction([that.__table], 'readonly');
				var store = transaction.objectStore(that.__table);
				var requestExpire = store.get(name + '__expire');
				transaction.oncomplete = function () {
					$wk.log('transaction oncomplete');
				};
				transaction.onabort = function () {
					$wk.log('transaction onabort');
				};
				transaction.onerror = function () {
					$wk.log('transaction onerror');
				};
				requestExpire.onerror = function (e) {
					$wk.log('requestExpire onerror:' + e.target.error.name);
					callback && callback('');
				};
				requestExpire.onsuccess = function (e) {
					$wk.log('requestExpire onsuccess');
					if (e.target.result && parseInt(e.target.result) > time) { // 检查是否过期
						var request = store.get(name);
						request.onerror = function (e) {
							$wk.log('request onerror:' + e.target.error.name);
							callback && callback('');
						};
						request.onsuccess = function (e) {
							$wk.log('request onsuccess');
							callback && callback(e.target.result ? e.target.result : '');
						};
					} else {
						$wk.log('request expire');
						callback && callback('');
					}
				};
			});
		} else if (that.__openDatabase in window) {
			that.__openDB(function (db) {
				db.transaction(function (tx) {
					tx.executeSql('SELECT value FROM ' + that.__table + ' WHERE name=\'' + name + '\' AND expire>' + time, [], function (tx, results) {
						$wk.log('request onsuccess');
						callback && callback(results.rows.length && results.rows.item(0).value ? JSON.parse(results.rows.item(0).value) : '');
					}, function () {
						$wk.log('request expire');
						callback && callback('');
					});
				});
			});
		} else {
			$wk.msg.showAlert('您的浏览器不支持本地缓存技术');
		}
	} catch (e) {
		$wk.log(e);
		callback && callback(false);
	}

	return this;
};

/**
 * 设置缓存
 * @param name string 缓存名称
 * @param value unknown 缓存值
 * @param expire number 过期时间（单位：秒）
 * @param callback function 回调方法
 * @returns {$wk.cache}
 */
$wk.cache.set = function (name, value, expire, callback) {
	try {
		var that = this;
		var time = parseInt(new Date().getTime() / 1000);
		if (that.__indexedDB in window) {
			that.__openDB(function (db) {
				var transaction = db.transaction([that.__table], 'readwrite');
				var store = transaction.objectStore(that.__table);
				var requestExpire = store.put(time + expire, name + '__expire');
				transaction.oncomplete = function () {
					$wk.log('transaction oncomplete');
				};
				transaction.onabort = function () {
					$wk.log('transaction onabort');
				};
				transaction.onerror = function () {
					$wk.log('transaction onerror');
				};
				requestExpire.onerror = function (e) {
					$wk.log('requestExpire onerror:' + e.target.error.name);
				};
				requestExpire.onsuccess = function () {
					$wk.log('requestExpire onsuccess');
					var request = store.put(value, name);
					request.onerror = function (e) {
						$wk.log('request onerror:' + e.target.error.name);
						callback && callback(false);
					};
					request.onsuccess = function () {
						$wk.log('request onsuccess');
						callback && callback(true);
					};
				};
			});
		} else if (that.__openDatabase in window) {
			that.__openDB(function (db) {
				db.transaction(function (tx) {
					tx.executeSql('SELECT value FROM ' + that.__table + ' WHERE name=\'' + name + '\'', [], function (tx, results) {
						if (results.rows.length && results.rows.item(0).value) {
							tx.executeSql('UPDATE ' + that.__table + ' SET value=\'' + JSON.stringify(value) + '\', expire=' + (time + expire) + ' WHERE name=\'' + name + '\'', [], function (tx, results) {
								callback && callback(true);
							}, function () {
								callback && callback(false);
							});
						} else {
							tx.executeSql('INSERT INTO ' + that.__table + ' (name, value, expire)VALUES(\'' + name + '\', \'' + JSON.stringify(value) + '\', ' + (time + expire) + ')', [], function (tx, results) {
								callback && callback(true);
							}, function () {
								callback && callback(false);
							});
						}
						$wk.log('request onsuccess');
					}, function () {
						callback && callback(false);
					});
				});
			});
		} else {
			$wk.msg.showAlert('您的浏览器不支持本地缓存技术');
		}
	} catch (e) {
		$wk.log(e);
		callback && callback(false);
	}
	return this;
};