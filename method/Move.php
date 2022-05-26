<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

/**
 * 移动文件或文件夹
 */
class Move extends Dav 
{
	public function start(){
		$path = $this->public.'/'.ltrim($_SERVER['PATH_INFO'],'/'); //旧路径
        $dest = $_SERVER['HTTP_DESTINATION']; //新路径
        $pos = strpos($dest, $_SERVER['SCRIPT_NAME']);
        $dest = substr($dest,$pos + strlen($_SERVER['SCRIPT_NAME']));
        $ndest = $this->public.'/'.ltrim($dest,'/'); //新路径绝对路径

        if(file_exists($path)){
            rename($path, $ndest);

            // $post_data['Path'] = '/'.ltrim($_SERVER['PATH_INFO'],'/'); //旧路径
            // $post_data['NewPath'] = $dest; //新路径
            // $data = $this->DavCurl->curl_upload_file(WebInfo::$ApiUrl."Index/moveFile",$post_data); //目录上传

            // $this->sqllog('文件移动',json_encode([
            //     'path'=>'/'.ltrim($_SERVER['PATH_INFO'],'/'),
            //     'dest'=>$dest,
            //     'data' => json_decode($data,true),
            // ],JSON_UNESCAPED_UNICODE));

            $this->DavXml->response_http_code(200);
        }else{
            $this->DavXml->response_http_code(403);
        }
	}
}