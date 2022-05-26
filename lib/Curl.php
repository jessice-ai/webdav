<?php
namespace app\lib;

class Curl{

	//POST请求
	function curl_post($url, $post_data){
	   //初始化
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//设置post方式提交
		curl_setopt($curl, CURLOPT_POST, 1);
		//设置TRUE可禁用对@前缀的支持
		curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
		//post提交的数据
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		//执行命令
		$data = curl_exec($curl);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		return $data;
	}


	//文件上传
	function curl_upload_file($url,$post_data){
		
        $headers = array(
            "Content-Type: multipart/form-data",
            "token:".$_SESSION['token']
        );
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置TRUE可禁用对@前缀的支持
        // curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        //post提交的数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        #file_put_contents('post.txt',json_encode($post_data).PHP_EOL.$data);
        return $data;
	}


}