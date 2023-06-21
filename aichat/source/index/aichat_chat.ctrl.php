<?php
class aichat_chatControl extends skymvc{
	public function onDefault(){
		
		$this->smarty->display("aichat_chat/index.html");
	}
	
	public function onApi(){
		session_write_close();
		$userid=M("login")->userid;
		$cfg=MM("aichat","aichat_config")->get();
		$aiuser=MM("aichat","aichat_user")->get($userid); 
		$url=$cfg["chat_api_host"];
		//$api_key=$aiuser["chatgpt_api_key"];
		$api_key=$cfg["chat_api_key"];
		$tagid=post("tagid","i"); 
		$data=array(
			"content"=>$_POST["content"],
			"oldMsg"=>json_encode($_POST["oldMsg"]),
			"tabIndex"=>$_POST["tabIndex"],
			"tagid"=>$tagid,
			"api_key"=>$api_key
		);
		 
		$res=curl_post($url,$data);
		//保存数据库
		$json=json_decode($res,true);
		if($json["error"]==0){
			$msg=M("mod_aichat_chat_msg")->selectRow("tagid=".$tagid);
			
			if(empty($msg)){
				M("mod_aichat_chat_msg")->insert(array(
					"userid"=>$userid,
					"tagid"=>$tagid
				));
				$msg=M("mod_aichat_chat_msg")->selectRow("tagid=".$tagid);
			}
			$arr=$_POST["oldMsg"];
			/*$arr[]=array(
				"role"=>"ask",
				"content"=>$_POST["content"]
			);
			*/
			$arr[]=array(
				"role"=>"answer",
				"content"=>$json["data"]["answer"]
			);
			$msg=M("mod_aichat_chat_msg")->update(array(
				"content"=>arr2str($arr)
			),"tagid=".$tagid);
		}
		
		echo $res;
	}
}