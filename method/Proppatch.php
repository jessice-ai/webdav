<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

class Proppatch extends Dav 
{

	public function start(){
		$path = $this->public.'/'.ltrim($_SERVER['PATH_INFO'],'/');
        echo <<<EOF
        <?xml version="1.0" encoding="utf-8" ?> 
        <D:multistatus xmlns:D="DAV:">  
          <D:response>  
            <D:href>{$path}</D:href> 
            <D:propstat> 
              <D:prop><D:owner/></D:prop> 
              <D:status>HTTP/1.1 403 Forbidden</D:status> 
              <D:responsedescription>
                <D:error><D:cannot-modify-protected-property/></D:error>
                Failure to set protected property (DAV:owner) 
              </D:responsedescription>
            </D:propstat>
          </D:response>
        </D:multistatus>
        EOF;

        $this->DavXml->response_http_code(207);
	}
}