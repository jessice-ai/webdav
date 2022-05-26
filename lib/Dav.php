<?php
namespace app\lib;

use app\config\WebInfo;
use app\lib\Status;
use app\lib\DavXml as DavXml;
use app\lib\Curl as DavCurl;
use app\lib\Get;

class Dav{

    protected $public;
    protected $DavXml;
    const MIME_TYPE_UNKNOW = 'application/unknow'; //默认资源类型
    const MIME_TYPE_DIR = 'application/x-director';  #Content-Type默认目录

    public function __construct()
    {
        $this->public = WebInfo::$HangingDirectory; #挂载目录
        $this->DavXml = new DavXml(); //XML结构组合
        $this->DavCurl = new DavCurl(); //实例化CURL
    }

    /**
     * 获取资源的MIME类型
     * @param string $filePath 资源全路径
     * @return string
     */
    public static function getFileMimeType($filePath)
    {
        $extName = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!empty($extName) && !empty($_SESSION['MIME_TYPE_LIST'][$extName])) {
            return $_SESSION['MIME_TYPE_LIST'][$extName];
        }
        $mimeType = mime_content_type($filePath);
        if (empty($mimeType)) {
            $mimeType = self::MIME_TYPE_UNKNOW;
        }
        if (!empty($extName) && !in_array($mimeType, ['inode/x-empty', self::MIME_TYPE_UNKNOW])) {
            $_SESSION['MIME_TYPE_LIST'][$extName] = $mimeType;
        }
        return $mimeType;
    }

	public function sqllog($msg,$data){
        !empty(WebInfo::$InterfaceLog) ? file_put_contents(WebInfo::$InterfaceLog, $msg.":".$data.PHP_EOL ,FILE_APPEND) : "" ;
    }

    public function llog($msg,$data){
        !empty(WebInfo::$LoginLog) ? file_put_contents(WebInfo::$LoginLog, $msg.":".$data.PHP_EOL ,FILE_APPEND) : "" ;
    }

    /**
      * 身份验证
      * @return bool
      */
    public function auth()
    {
        try{
            $this->llog('用户登录 - SESSION',json_encode($_SESSION));
            if(array_key_exists('auth',$_SESSION) and $_SESSION['auth']==true and array_key_exists('token',$_SESSION) and !empty($_SESSION['token'])){ #登陆状态
                return true;
            }

            if (array_key_exists('HTTP_AUTHORIZATION',$_SERVER) and !empty($_SERVER['HTTP_AUTHORIZATION'])) {
                 $this->llog('用户登录 - HTTP_AUTHORIZATION',$_SERVER['HTTP_AUTHORIZATION']);
                $Authorization = $_SERVER['HTTP_AUTHORIZATION'];
                $authInfo = preg_split('/\s+/', $Authorization);
                $authInfo = base64_decode(trim($authInfo[1]));
                $authInfo = explode(':', $authInfo); // 账号 $authInfo[0]  密码 $authInfo[1]
                
                if(!empty($authInfo) and count($authInfo)==2) {
                    $param['ID'] = $authInfo[0];
                    $param['Password'] = $authInfo[1];
                    $data = json_decode($this->DavCurl->curl_post(WebInfo::$ApiUrl."User/getToken",$param),true);
                    $this->llog('用户登录 - 用户',json_encode([
                        $authInfo[0],$authInfo[1]
                    ]));

                    $this->llog('用户登录 - 接口',json_encode($data));

                    if($data['code']==1){ //用户账号密码校验正确
                        $_SESSION['auth'] = true;
                        $_SESSION['token'] = $data['data']; //用户登陆返回的Token值
                        $this->llog('用户登录 - SESSION',json_encode($_SESSION).PHP_EOL);
                        // $result = true;
                    }
                }
            }
        } catch (Exception $e) {
            file_put_contents(WebInfo::$Exception, $e->getMessage() ,FILE_APPEND);
        }
    }

    
    
}