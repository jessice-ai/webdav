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
class Options extends Dav 
{
	public function start(){
        
		header('Allow: OPTIONS,GET,HEAD,DELETE,PROPFIND,PROPPATCH,COPY,MKCOL,MOVE,PUT,LOCK,UNLOCK');
        header('Accept-Charset: utf-8');
        header('MS-Author-Via: DAV');
        header('Date: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
	}
}