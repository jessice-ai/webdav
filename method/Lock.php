<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

class Lock extends Dav 
{
	public function start(){
		$this->DavXml->response_http_code(501);
	}
}