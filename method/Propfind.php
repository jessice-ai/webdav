<?php
namespace app\method;
use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;
use app\lib\Dav;

/**
 * 文件属性详情,也可返回目录中文件列表及目录本身属性
 * WebDAV客户端 遇到 Depth: 0，只需要返回目录本身属性信息，不需要目录下其他文件
 * WebDAV客户端 遇到 Depth: 1, 要求列出远程目录中所有文件的列表
 * @return [type] [description]
 */

class Propfind extends Dav
{

	public function start(){

		try{
            // if(!array_key_exists('auth',$_SESSION) or $_SESSION['auth']!=1){ #用户未登录
            //     header('HTTP/1.1 401 Unauthorized');
            //     header('WWW-Authenticate: Basic realm="login WebDav site"');
            //     $this->auth(); 
            //     exit();
            // }
            
            $path = $this->public.'/'.ltrim($_SERVER['PATH_INFO'] ?? '','/');
            $dav_base_dir = $_SERVER['SCRIPT_NAME']. '/'.ltrim($_SERVER['PATH_INFO'] ?? '','/');

            /**
             * 需求:返回指定目录下 文件及目录 XML格式
             * 协议:propfind
             * 请求 http://127.0.0.1:8150/webdav.php #public 根目录
             * 请求 http://127.0.0.1:8150/webdav.php/mobile #public/mobile 二级目录
             * 
             */
            
            # WebDAV客户 遇到 Depth: 0 ，只需要返回目录本身属性信息，不需要目录下其他文件
            if(array_key_exists('HTTP_DEPTH',$_SERVER) and $_SERVER['HTTP_DEPTH'] == 0){
                if(is_file($path)){
                    $response_text = $this->DavXml->response_file($dav_base_dir,filemtime($path),filesize($path),WebInfo::http_code(200));
                }else if(is_dir($path)){
                    $response_text = $this->DavXml->response_basedir($dav_base_dir,filemtime($path),WebInfo::http_code(200));
                }else{
                    $Fiter = ['/Desktop.ini','/AutoRun.inf','/desktop.ini','/.RaiDrive'];
                        #默认Windows文件 "Desktop.ini" 或 "Autorun.inf",无论这些文件是否存在于WebDAV服务器,都不予检查
                    if(!in_array(trim($_SERVER['PATH_INFO']), $Fiter)){
                        // file_put_contents('path.txt',ltrim($_SERVER['PATH_INFO'].PHP_EOL),FILE_APPEND); //遇到特殊格式文件写入日志
                        $this->DavXml->response_http_code(404);
                        return;
                    }
                }
                
                $this->DavXml->response_http_code(207);
                header('Content-Type: text/xml; charset=utf-8');
                $result = $this->DavXml->response($response_text);
                echo $result;
                exit();
            }
            
            $files = scandir($path);
            //WebDAV客户端 遇到 Depth: 1, 要求列出远程目录中所有文件的列表
            $response_text = $this->DavXml->response_basedir($dav_base_dir,filemtime($path),WebInfo::http_code(200));
            #循环指定目录下

            foreach ($files as $file){
                if($file == '.' || $file == '..'){ #过滤 . .. 数据
                    continue;
                }
                $file_path  = $path.'/'.$file; #绝对路径 D:\phpstudy_pro\WWW/public/asss//6.txt
                $mtime = filemtime($file_path); #获取文件上次修改时间
                
                if(is_dir($file_path)){
    
                    /**
                     * @param 目录 /webdav.php/asss//t
                     * @param 上次修改时间戳 1652864513
                     * @param 状态码
                     * 
                     */
                    $response_text.= $this->DavXml->response_dir($dav_base_dir.'/'.$file,$mtime,WebInfo::http_code(200));
                }

                if(is_file($file_path)){

                    $response_text.= $this->DavXml->response_file($dav_base_dir.'/'.$file, $mtime,filesize($file_path),WebInfo::http_code(200));
                }
            }

            $this->DavXml->response_http_code(207);
            header('Content-Type: text/xml; charset=utf-8');
            echo $this->DavXml->response($response_text);

        } catch (Exception $e) {
            file_put_contents(WebInfo::$Exception, $e->getMessage() ,FILE_APPEND);
        }
	}
}

