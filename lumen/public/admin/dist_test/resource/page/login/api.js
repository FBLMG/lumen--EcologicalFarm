// 登录
$wk.api.login = function(username, password, options) {
    $wk.api.post('login', {
        username: username,
        password: password
    }, options);
}