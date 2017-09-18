<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    private $phpInput;
    private $phpInputJson;
    private $phpInputStatus = false;

    public function __construct()
    {
        if ($_ENV['params']['apiAllowCrossDomain']) {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, Range, Origin, x-requested-with');
            header('Access-Control-Request-Methods: POST,GET,PUT,DELETE,OPTIONS');
            header('Access-Control-Allow-Methods: POST,GET,PUT,DELETE,OPTIONS');
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Expose-Headers: Content-Range');
            header('P3P: CP=CAO PSA OUR');
        }
        //
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }
        $_GET['page'] = $this->request('pageNumber', 0) + 1;
        Log::info('request_GET：' . $_GET['page']);
    }

    //

    /**
     * API结果输出（可直接使用业务类的returnSuccess、returnError的返回结果）
     * @param $return
     */
    protected function echoReturn($return)
    {
        $result = json_encode($return);
        Log::info('echoReturn：' . $result);
        echo $result;
    }

    /**
     * API结果成功
     * @param array $data
     */
    protected function echoSuccess($data = array())
    {
        $result = json_encode(array(
            'code' => 0,
            'data' => $data
        ));
        Log::info('echoSuccess：' . $result);
        echo $result;
    }

    /**
     * API结果错误
     * @param $code
     * @param $message
     */
    protected function echoError($code, $message)
    {
        $result = json_encode(array(
            'code' => $code,
            'message' => $message
        ));
        Log::info('echoError：' . $result);
        echo $result;
    }

    /**
     * 获取POST请求数据
     * @param $key
     * @param string $default
     * @return float|int|string
     */
    protected function post($key, $default = '')
    {
        if (isset($_POST[$key])) {
            return $this->formatValueWithDefaultType($_POST[$key], $default);
        } else {
            return $default;
        }
    }

    /**
     * 获取GET请求数据
     * @param $key
     * @param string $default
     * @return float|int|string
     */
    protected function get($key, $default = '')
    {
        if (isset($_GET[$key])) {
            return $this->formatValueWithDefaultType($_GET[$key], $default);
        } else {
            return $default;
        }
    }

    /**
     * 获取JSON请求数据
     * @param $key
     * @param string $default
     * @return bool|float|int|string
     */
    protected function json($key, $default = '')
    {
        if ($this->phpInputStatus === false) {
            $this->phpInput = file_get_contents("php://input");
            $this->phpInputJson = json_decode($this->phpInput);
            $this->phpInputStatus = true;
            Log::info('php://input：' . (strlen($this->phpInput) > 20000 ? '（md5）' . md5($this->phpInput) : $this->phpInput));
        }
        if ($default === 'isset-check') {
            return isset($this->phpInputJson->$key);
        }
        if (isset($this->phpInputJson->$key)) {
            return $this->formatValueWithDefaultType($this->phpInputJson->$key, $default);
        } else {
            return $default;
        }
    }

    /**
     * 获取请求数据
     * 此方法同时支持POST和GET请求数据，如果配置 apiRequestOnlyPost:true 则表示只支持POST请求
     * @param $key
     * @param string $default
     * @return float|int
     */
    protected function request($key, $default = '')
    {
        if ($_ENV['params']['apiRequestOnlyJson'] || $this->json($key, 'isset-check')) {
            $value = $this->json($key, $default);
        } else {
            if (isset($_REQUEST[$key])) {
                $value = $this->formatValueWithDefaultType($_REQUEST[$key], $default);
            } else {
                $value = $default;
            }
        }
        if (is_array($value) || is_object($value)) {
            Log::info('request：' . $key . '/' . json_encode($value));
        } else {
            Log::info('request：' . $key . '/' . (strlen($value) > 10000 ? '（md5）' . md5($value) : $value));
        }
        return $value;
    }

    /**
     * 获取请求URL
     * @return string
     */
    protected function getRequestUri()
    {
        $requestUri = '';
        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            // check this first so IIS will catch
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (isset($_SERVER['REDIRECT_URL'])) {
            // Check if using mod_rewrite
            $requestUri = $_SERVER['REDIRECT_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
            // IIS 5.0, PHP as CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
		$requestUris = explode('?', $requestUri);
        return $requestUris[0];
    }

    /**
     * 根据$default的类型格式化$value的值
     * @param $value
     * @param $default
     * @return float|int
     */
    private function formatValueWithDefaultType($value, $default)
    {
        if (is_integer($default)) {
            return intval($value);
        } elseif (is_float($default)) {
            return floatval($value);
        } else {
            return $value;
        }
    }
}
