<?php
/******************************************************
 * author : ruskinli
 * date : 2019/05/05
 * desc : php sdk of tts
 ******************************************************/
require_once ('Config.php');

/**
 * 获取签名鉴权
 * reqArr 请求原始数据
 * method 请求方式 POST
 * domain 请求域名
 * path 请求路径
 * secretKey 用户秘钥
 * output str 鉴权签名signature
 */
function createSign($reqArr, $method, $domain, $path, $secretKey) {
	$signStr = "";
	$signStr .= $method;
	$signStr .= $domain;
	$signStr .= $path;
	$signStr .= "?";
	ksort($reqArr, SORT_STRING);

	foreach ($reqArr as $key => $val) {
		if (is_float($val)){
			$signStr .= $key . "=" . sprintf("%g",$val) . "&";
		}else{
			$signStr .= $key . "=" . $val . "&";
		}
	}
	$signStr = substr($signStr, 0, -1);
	$signStr = base64_encode(hash_hmac('SHA1', $signStr, $secretKey, true));

	return $signStr;
}

/**
 * http post请求
 * url 请求链接地址
 * data 请求数据
 * rsp_str  回包数据
 * http_code 请求状态码
 * method 请求方式，默认POST
 * timeout 超时时间
 * cookies cookie
 * header http请求头
 * output int 请求结果
 */
function http_curl_exec($url, $data, & $rsp_str, & $http_code, $method = 'POST', $timeout = 10, $cookies = array (), $headers = array ()) {
	$ch = curl_init();
	if (!curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1)) {
		//echo 'http_curl_ex set returntransfer failed';
		return -1;
	}
	 
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	if (count($headers) > 0) {
		//Log::debug('http_curl_ex set headers');
		if (!curl_setopt($ch, CURLOPT_HTTPHEADER, $headers)) {
			//echo 'http_curl_ex set httpheader failed';
			return -1;
		}
	}

	if ($method != 'GET') {
		$data = is_array($data) ? json_encode($data) : $data;
		//Log::debug('data (non GET method) : '.$data);
		if (!curl_setopt($ch, CURLOPT_POSTFIELDS, $data)) {
			//echo 'http_curl_ex set postfields failed';
			return -1;
		}
	} else {
		$data = is_array($data) ? http_build_query($data) : $data;
		if (strpos($url, '?') === false) {
			$url .= '?';
		} else {
			$url .= '&';
		}
		$url .= $data;
	}
	//echo "Req data :" . json_encode($data);
	if (!empty ($cookies)) {
		$cookie_str = '';
		foreach ($cookies as $key => $val) {
			$cookie_str .= "$key=$val; ";
		}

		if (!curl_setopt($ch, CURLOPT_COOKIE, trim($cookie_str))) {
			//echo 'http_curl_ex set cookie failed';
			return -1;
		}
	}

	if (!curl_setopt($ch, CURLOPT_URL, $url)) {
		//echo 'http_curl_ex set url failed';
		return -1;
	}

	if (!curl_setopt($ch, CURLOPT_TIMEOUT, $timeout)) {
		//echo 'http_curl_ex set timeout failed';
		return -1;
	}

	if (!curl_setopt($ch, CURLOPT_HEADER, 0)) {
		//echo 'http_curl_ex set header failed';
		return -1;
	}

	$rsp_str = curl_exec($ch);
	if ($rsp_str === false) {
		//var_dump(curl_error($ch));
		curl_close($ch);
		return -2;
	}

	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	return 0;
}

/**
 * 请求获取语音
 * output str 音频pcm格式
 */
function getVoice() {
	$reqArr = array ();
	$reqArr['Action'] = Config :: $ACTION;
	$reqArr['AppId'] = Config :: $APPID;
	$reqArr['Codec'] = Config::$CODEC;
	$reqArr['Expired'] = Config::$EXPIRED + time(); //表示为离线识别
	$reqArr['ModelType'] = Config :: $MODEL_TYPE;
	$reqArr['PrimaryLanguage'] = Config::$PRIMARY_LANGUAGE;
	$reqArr['ProjectId'] = Config :: $PROJECT_ID;
	$reqArr['SampleRate'] = Config :: $SAMPLE_RATE;
	$reqArr['SecretId'] = Config :: $SECRET_ID;
	$reqArr['SessionId'] = Config::$SESSION_ID;
	$reqArr['Speed'] = Config::$SPEED;
	$reqArr['Text'] = Config::$TEXT;
	$reqArr['Timestamp'] = time();
	$reqArr['VoiceType'] = Config::$VOICET_YPE;
	$reqArr['Volume'] = Config::$VOLUME;

	$serverUrl = "https://tts.cloud.tencent.com/stream";

	$autho = createSign($reqArr, "POST", "tts.cloud.tencent.com", "/stream", Config :: $SECRET_KEY);
	/*echo "datalen :" . $datalen;*/

	$header = array (
		'Authorization: ' . $autho,
		'Content-Type: ' . 'application/json',
	);

	$rsp_str = "";
	$http_code = -1;
	$ret = http_curl_exec($serverUrl, $reqArr, $rsp_str, $http_code, 'POST', 15, array (), $header);
	if ($ret != 0) {
		//echo "http_curl_exec failed \n";
		return false;
	}
	/*echo "Response String: \n" . $rsp_str . "\n";*/

	return $rsp_str;
}

/**
 * 获取guid
 * output str uuid
 */
function guid(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = 
                substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12);
        return $uuid;
    }
}
?>



