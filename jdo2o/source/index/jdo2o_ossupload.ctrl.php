<?php
/***阿里云直传**/
function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
}	
class jdo2o_ossuploadControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onInit(){
		 
	}
	
	public function onDefault(){
		
	}
	
	public function onAuth(){
		/***config****/
		$config=M("open_alioss")->selectRow("status=1");
		$id= $config['appid'];
		$key= $config['appkey'];
		$url="https://".$config['bucket'].".".$config['endpoint'];
		$bucket=$config['bucket'];
		 
		$callbackUrl = HTTP_HOST."index.php/ossupload/callback";
		/**end config**/
		$dir=isset($_GET['dir'])?str_replace("/","",$_GET['dir'])."/":"video/";
		$options = array();
		$options['expiration'] = gmt_iso8601(time()+30); /// 授权过期时间
		$conditions = array();
		array_push($conditions, array('bucket'=>$bucket));
		$content_length_range = array();
		array_push($content_length_range, 'content-length-range');
		array_push($content_length_range, 0);
		array_push($content_length_range, 104857500);
		array_push($conditions, $content_length_range);
		$options['conditions'] = $conditions;
		$policy = base64_encode(stripslashes(json_encode($options)));
		$sign = base64_encode(hash_hmac('sha1',$policy,$key, true));
		
		
		$callback_param = array('callbackUrl'=>$callbackUrl, 
		             'callbackBody'=>'filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}', 
		             'callbackBodyType'=>"application/x-www-form-urlencoded");
		$callback_string = json_encode($callback_param);
		
		$callbackbody = base64_encode($callback_string);
		$json=array(
			"accessid"=>$id,
			"policy"=>$policy,
			"sign"=>$sign,
			"key"=>$dir.date("Y/m/d/").time().session_id(),
			"callback"=>$callbackbody,
			"url"=>$url
		);
		echo json_encode($json);
	}
	
	
}
?>