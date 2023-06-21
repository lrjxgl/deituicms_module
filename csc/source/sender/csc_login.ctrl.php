<?php
class csc_loginControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$site=M("site")->get();
		$this->smarty->goAssign(array(
			"site"=>$site
		));
		$this->smarty->display("csc_login/index.html");
	}
	
	public function onSendSms(){
		$telephone=get_post('telephone','h');
		if(!is_tel($telephone)){
			$this->goall("请输入正确手机号码",1);
		}
		if(!M("mod_csc_sender")->selectRow(array("where"=>"telephone='".$telephone."' "))){
			//$this->goall("手机用户不存在",1);
		}
		$t=cache()->get("sender_login_sms_expire_".$telephone);
		if($t){
			$this->goall("请过".(60-(time()-$t))."秒再发送",1);
		}
		$yzm=rand(111111,999999);
		 
		$content="【".SMS_QIANMING."】验证码：".$yzm."，请您5分钟内登录";
		$content=array(
			"content"=>$content,
			"code"=>$yzm,
			"tpl"=>"login"
			
		);
		
		
		
		$key="sender_login_sms".$telephone.$yzm;
		if(SMSTEST==1){
			cache()->set($key,1,300);
			$this->goAll("短信已发送，请在5分钟内登录".$yzm);
		}
		$res=M("sms")->sendSms($telephone,$content);
		if($res){
			cache()->set($key,1,300);
			cache()->set("sender_login_sms_expire_".$telephone,time(),60);
			$this->goAll("短信已发送，请在5分钟内登录");
		}else{
			$this->goAll("短信发送失败",1);
		}
	}
	public function onLoginSave(){
		$yzm=get_post('yzm','h');
		$telephone=get_post('telephone','h');
		$key="sender_login_sms".$telephone.$yzm;
		if(!cache()->get($key)){
			$this->goAll("手机验证码出错",1);
		}
		$sender=M("mod_csc_sender")->selectRow(array(
			"where"=>"telephone='".$telephone."' ",
			"fields"=>"senderid,truename,shopid"
		));
		if(!$sender){
			M("mod_csc_sender")->insert(array(
				"telephone"=>$telephone
			));
			$sender=M("mod_csc_sender")->selectRow(array(
				"where"=>"telephone='".$telephone."' ",
				"fields"=>"senderid,truename,shopid"
			));
		}else{
			if($sender["status"]>3){
				$this->goAll("您的账号已被禁了",1);
			}
		}
		
		$_SESSION["mcsc_sender"]=$sender;
		$token="mcsc_sender_token".md5(time());
		cache()->set($token,$sender["senderid"],240);
		setcookie("mcsc_sender_token",$token,time()+3600*24*10);
		$this->goAll("登录成功");
	}
	
	public function onLoginOut(){
		unset($_SESSION["mcsc_sender"]);
		$token=$_COOKIE["mcsc_sender_token"];
		setcookie("mcsc_sender_token",0,time()-10);
		cache()->del($token);
		$this->goAll("注销成功");
			
	}
	
}
?>