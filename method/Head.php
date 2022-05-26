<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

class Head extends Dav 
{
	public function start(){
		// $auth = $this->auth();
        header('Content-Type: application/octet-stream');
        $path = $this->public.'/'.ltrim($_SERVER['PATH_INFO'],'/');
        // file_put_contents('path',$path);
        if(is_file($path)){
            header('Content-Length: '.filesize($path));
            $lastmod = filemtime($path);
            $lastmod = gmdate("D, d M Y H:i:s", $lastmod)." GMT";
            header('Last-Modified: '.$lastmod);
        }else{
            // file_put_contents('headx.txt','head - 404');
            $this->DavXml->response_http_code(404);
        }
	}
}