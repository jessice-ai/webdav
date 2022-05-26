<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Dav;

class Get extends Dav 
{
	public function start(){
		$path = $this->public.'/'.ltrim($_SERVER['PATH_INFO'],'/');
        $MimeType = $this->getFileMimeType($path);

        header('Content-Type: '.$MimeType.'; charset=utf-8');
        echo 'Content-Type: '.$MimeType.'; charset=utf-8';
        exit();
        if(is_file($path)){
            $fh = fopen($path,'r');
            $oh = fopen('php://output','w');
            stream_copy_to_stream($fh, $oh);
            fclose($fh);
            fclose($oh);
        }else{
            // file_put_contents('get.txt','get404');
            $this->DavXml->response_http_code(404);
        }
	}
}