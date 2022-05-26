<?php
namespace app\lib;

use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;

use app\method\Propfind;
use app\method\Put;
use app\method\Head;
use app\method\Get;
use app\method\Delete;
use app\method\Move;
use app\method\Mkcol;
use app\method\Options;
use app\method\Lock;
use app\method\Proppatch;

class webdav {

    public function options()
    {   
        $options = new options();
        $options->start();
    }

    public function head()
    { 
        $Head = new Head();
        $Head->start();
    }


    public function get()
    {
        $Get = new Get();
        $Get->start();
    }

    // 文件上传走这里,空目录上传不走这里
    public function put()
    {
        $Put = new Put();
        $Put->start();
    }

    /**
     * WebDAV客户端 遇到 Depth: 0，只需要返回目录本身属性信息，不需要目录下其他文件
     * WebDAV客户端 遇到 Depth: 1, 要求列出远程目录中所有文件的列表
     * 
     * @return [type] [description]
     */
    public function propfind()
    {  
        $Propfind = new Propfind();
        $Propfind->start();
    }

    //资源目录删除
    public function delete()
    {
        $Delete = new Delete();
        $Delete->start();
    }

    public function lock()
    {
        $Lock = new Lock();
        $Lock->start();
    }

    public function proppatch()
    {
        $Proppatch = new Proppatch();
        $Proppatch->start();
    }

    //目录创建
    public function mkcol()
    {
        $mkcol = new mkcol();
        $mkcol->start();
    }

    //移动文件或文件夹
    public function move()
    {
        $move = new move();
        $move->start();
    }
}
