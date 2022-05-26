<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

/**
 * 目录创建
 */
class Mkcol extends Dav 
{
	public function start(){
		$pathinfo = ltrim($_SERVER['PATH_INFO'],'/');
        $path = $this->public.'/'.$pathinfo;
        if(!file_exists($path)){
            mkdir($path);
            // $post_data['Path'] = "/".$pathinfo;
            // $post_data['IsDir'] = 1; //1、文件夹，0、文件
            // $data = $this->DavCurl->curl_upload_file(WebInfo::$ApiUrl."Index/addFileOrDir",$post_data); //目录上传

            // $this->sqllog('文件夹上传',json_encode([
            //     'path'=>"/".$pathinfo,
            //     'data' => json_decode($data,true),
            // ],JSON_UNESCAPED_UNICODE));

            $this->DavXml->response_http_code(200);
        }else{
            $this->DavXml->response_http_code(403);
        }
	}
}