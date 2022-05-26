<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

class Delete extends Dav 
{
	public function start(){
		header('Content-Type: application/octet-stream');
        $path = $this->public.'/'.ltrim($_SERVER['PATH_INFO'],'/');

        $param['Path'] = "/".basename($path);
        if(is_file($path)){
            $un = unlink($path);
            $param['IsDir'] = 0; //文件
        }

        if(is_dir($path)){
            $un = rmdir($path);
            $param['IsDir'] = 1; //文件夹
        }

        #$data = $this->DavCurl->curl_post(WebInfo::$ApiUrl."Index/delResourceInfo",$param);
        #$this->sqllog(is_file($path)?"文件删除":"目录删除",$data);
        if($un){
            $this->DavXml->response_http_code(200);
        }else{
            $this->DavXml->response_http_code(503);
        }
	}
}