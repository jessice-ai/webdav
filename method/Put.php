<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

/**
 * 文件上传
 */
class Put extends Dav
{
	public function start(){
		$input = fopen("php://input",'r'); // 只读流 允许从请求体中读取原始数据,不依赖 php.ini 指令,不适用于 enctype="multipart/form-data"
        try{
            $path = $this->public.'/'.ltrim($_SERVER['PATH_INFO'],'/'); //结构 D:\phpstudy_pro\WWW\gitlab\php_webdav/public/a1.txt
            $fh = fopen($path,'w');
            @$res = stream_copy_to_stream($input, $fh);
            fclose($fh);
            /**
             * 数据流复制完成后,调用接口
             * 否则回出现文件上传内容为空,文件类型为.txt 情况
             */
            if($res==true){

                #Webdav服务端文件绝对路径
                $Server_Path = WebInfo::$HangingDirectory.$_SERVER['PATH_INFO']; //例如:D:\phpstudy_pro\WWW\gitlab\php_webdav/public/999/a2.txt
    
                if(is_file($Server_Path)){
                    $post_data = [
                        // "Path"  => "/119a.png",
                        "Path"  => $_SERVER['PATH_INFO'],
                        "IsDir" => 0
                    ];
                    $file = $Server_Path;

                    // php 5.5+ 需要使用 curl_file_create 方法
                    if (function_exists('curl_file_create')) {
                        $post_data['File'] = curl_file_create($file);
                    } else {
                        $post_data['File'] = '@' . $file;
                    }
                    
                    $data = $this->DavCurl->curl_upload_file(WebInfo::$ApiUrl."Index/addFileOrDir", $post_data);
                    $this->sqllog('文件上传',json_encode([
                        'path'=> $post_data['Path'],
                        'data' => json_decode($data,true),
                    ],JSON_UNESCAPED_UNICODE));
                }
            }

        }catch (Throwable $throwable){
            $this->DavXml->response_http_code(503);
            // echo $throwable->getMessage();
            file_put_contents(WebInfo::$Exception, $throwable->getMessage() ,FILE_APPEND);
        }
	}
}