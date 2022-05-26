<?php
namespace app\lib;
use app\config\WebInfo;

class DavXml{

	//XML格式组装
	public function response_basedir($dir, $lastmod, $status)
	{
	    $lastmod = gmdate("D, d M Y H:i:s", $lastmod)." GMT";
	    $fmt = <<<EOF
		<D:response>
		        <D:href>{$dir}</D:href>
		        <D:propstat>
		            <D:prop>
		                <D:getlastmodified>{$lastmod}</D:getlastmodified>
		                <D:resourcetype>
		                    <D:collection/>
		                </D:resourcetype>
		            </D:prop>
		            <D:status>{$status}</D:status>
		        </D:propstat>
		    </D:response>
		EOF;
		return $fmt;
	}

	//目录结构组装
	function response_dir($dir, $lastmod, $status)
	{
	    $lastmod = gmdate("D, d M Y H:i:s", $lastmod)." GMT";
	    $fmt = <<<EOF
		  <D:response>
		    <D:href>{$dir}</D:href>
		    <D:propstat>
		      <D:prop>
		        <D:resourcetype>
		          <D:collection></D:collection>
		        </D:resourcetype>
		        <D:getlastmodified>{$lastmod}</D:getlastmodified>
		        <D:displayname/>
		      </D:prop>
		      <D:status>{$status}</D:status>
		    </D:propstat>
		  </D:response>
		EOF;
	    // /dav/
	    //Sun, 11 Apr 2021 16:23:30 GMT
	    // HTTP/1.1 200 OK

	    return $fmt;
	}

	//文件结构组装
	function response_file($file_path, $lastmod, $file_length, $status)
	{
	    $lastmod = gmdate("D, d M Y H:i:s", $lastmod)." GMT";
	    $tag = md5($lastmod.$file_path);
	    $fmt = <<<EOF
		  <D:response>
		    <D:href>{$file_path}</D:href>
		    <D:propstat>
		      <D:prop>
		        <D:resourcetype/>
		        <D:getcontentlength>{$file_length}</D:getcontentlength>
		        <D:getetag>"{$tag}"</D:getetag>
		        <D:getcontenttype/>
		        <D:displayname/>
		        <D:getlastmodified>{$lastmod}</D:getlastmodified>
		      </D:prop>
		      <D:status>{$status}</D:status>
		    </D:propstat>
		  </D:response>
		EOF;
	    // /dav/%E6%96%B0%E5%BB%BA%E6%96%87%E6%9C%AC%E6%96%87%E6%A1%A3.txt
	    // 0
	    // HTTP/1.1 200 OK
	    // Mon, 12 Apr 2021 06:32:44 GMT
	    return $fmt;

	}


	function response($text)
	{
	    return <<<EOF
		<?xml version="1.0" encoding="utf-8"?>
		<D:multistatus xmlns:D="DAV:">
		  {$text}
		</D:multistatus>
		EOF;
	}

	public function response_http_code($num)
	{
	    header(WebInfo::http_code($num));
	}

}