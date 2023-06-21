<?php
/**
 * 机器翻译 WebAPI 接口调用示例
 * 运行前：请先填写Appid、APIKey、APISecret
 * 
 * 1.接口文档（必看）：https://www.xfyun.cn/doc/nlp/xftrans/API.html
 * 2.错误码链接：https://www.xfyun.cn/document/error-code （错误码code为5位数字）
 * @author iflytek
 */
class xffanyi {
	public $app_id;
	public $api_sec;
	public $api_key;
	function __construct($cfg){
		$this->app_id=$cfg["app_id"];
		$this->api_sec=$cfg["api_sec"];
		$this->api_key=$cfg["api_key"];
	}
    function tocurl($url, $header, $content){
        $ch = curl_init();
        if(substr($url,0,5)=='https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        if (is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        curl_setopt($ch, CURLOPT_POST, true);
        if (!empty($content)) {
            if (is_array($content)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($content));
            } else if (is_string($content)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
            }
        }
        $response = curl_exec($ch);
        $error=curl_error($ch);
        //var_dump($error);
        if($error){
            die($error);
        }
        $header = curl_getinfo($ch);

        curl_close($ch);
        $data = array('header' => $header,'body' => $response);
        return $data;
    }
    function xfyun($text= "我是中国人") {
		
		/*//在控制台-我的应用-机器翻译获取
        $app_id = "2f47c0ef";
		//在控制台-我的应用-机器翻译获取
        $api_sec = "Y2MxYzUzZDc1YThkZDUxNmZjZDkyZGFj";
		//在控制台-我的应用-机器翻译获取
        $api_key = "af1e6fdd87ace24cdc25653c41b5e76b";
		*/
	   $app_id=$this->app_id;
	   $api_sec=$this->api_sec;
	   $api_key=$this->api_key;
		// 机器翻译接口地址
        $url = "https://itrans.xfyun.cn/v2/its";

        //body组装
        
        $body = json_encode($this->getBody($app_id, "cn", "en", $text));

        // 组装http请求头
        $date =gmdate('D, d M Y H:i:s') . ' GMT';

        $digestBase64  = "SHA-256=".base64_encode(hash("sha256", $body, true));
        $builder = sprintf("host: %s
date: %s
POST /v2/its HTTP/1.1
digest: %s", "itrans.xfyun.cn", $date, $digestBase64);
        // echo($builder);
        $sha = base64_encode(hash_hmac("sha256", $builder, $api_sec, true));

        $authorization = sprintf("api_key=\"%s\", algorithm=\"%s\", headers=\"%s\", signature=\"%s\"", $api_key,"hmac-sha256",
            "host date request-line digest", $sha);

        $header = [
            "Authorization: ".$authorization,
            'Content-Type: application/json',
            'Accept: application/json,version=1.0',
            'Host: itrans.xfyun.cn',
            'Date: ' .$date,
            'Digest: '.$digestBase64
        ];
        $response = $this->tocurl($url, $header, $body);
		$res=json_decode($response['body'],true);
		 
		if($res["code"]==0){
			return array(
				"error"=>0,
				"content"=>$res["data"]["result"]["trans_result"]["dst"]
			);
		}else{
			return array(
				"error"=>1,
				"content"=>""
			);
		}
        
    }

    function getBody($app_id, $from, $to, $text) {
        $common_param = [
            'app_id'   => $app_id
        ];

        $business = [
            'from' => $from,
            'to'   => $to,
        ];

        $data = [
            "text" => base64_encode($text)
        ];

        return $body = [
            'common' => $common_param,
            'business' => $business,
            'data' => $data
        ];
    }
}
/* 
$fy = new xffanyi();
print_r($fy->xfyun("蓝天上飞过一只小鸟"));
*/

