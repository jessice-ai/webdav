<?php
require 'vendor/autoload.php';
use app\lib\webdav;
use app\config\WebInfo;

ini_set('display_errors',1);            //显示PHP运行时错误信息
ini_set('display_startup_errors',1);    //显示PHP启动时错误
error_reporting(E_ALL);                 //设置报告错误级别
session_start();
$dav = new webdav();
$request_method = strtolower($_SERVER['REQUEST_METHOD']);

if(!is_dir(BASE_ROOT."/log")){ mkdir(BASE_ROOT."/log"); }
if(!is_dir(BASE_ROOT."/public")){ mkdir(BASE_ROOT."/public"); }

$header_text = "";

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
        return $headers;
    }
}

foreach (getallheaders() as $name => $value) {
    $header_text.="$name: $value\n";
}

$input = file_get_contents("php://input");


!empty(WebInfo::$HeadLog) ? file_put_contents(WebInfo::$HeadLog, "head:".$request_method.' '.$_SERVER['REQUEST_URI'].PHP_EOL.$header_text. PHP_EOL ,FILE_APPEND) : "" ;
if (method_exists($dav, $request_method)) {
    $dav->$request_method();
} else {
    // 405 Method Not Allowed
    response_http_code(405);
}