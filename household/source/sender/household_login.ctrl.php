<?php
class household_loginControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$this->smarty->display("household_login/index.html");
	}
	
	public function onLogout(){
		MM("household","household_sender")->logout();
		$this->goAll("success");
	}
	
	public function onSendSms(){
		$telephone=get_post('telephone','h');
		if(!is_tel($telephone)){
			$this->goall("请输入正确手机号码",1);
		}
		 
		$t=cache()->get("household_sender_".$telephone);
		if($t){
			$this->goall("请过".(60-(time()-$t))."秒再发送",1);
		}
		$yzm=rand(1111,9999);
		 
		$content="【".SMS_QIANMING."】验证码：".$yzm."，请您5分钟内完成注册";
		$content=array(
			"content"=>$content,
			"code"=>$yzm,
			"tpl"=>"reg"
		);
		$key="household_sender_sms".$telephone.$yzm;
		if(defined("SMS_TEST") && SMS_TEST==1){
			cache()->set($key,1,300);
			cache()->set("household_sender_".$telephone,time(),60);
			$this->goAll("短信已发送，请在5分钟内验证注册".$yzm);
		} 
		$res=M("sms")->sendSms($telephone,$content);
		
		
		if($res){
			cache()->set($key,1,300);
			cache()->set("household_sender_".$telephone,time(),60);
			$this->goAll("短信已发送，请在5分钟内验证注册");
		}else{
			$this->goAll("短信发送失败",1);
		}
	}
	public function onSave(){
		$yzm=get_post('yzm','h');
		$telephone=post('telephone','h');
		$key="household_sender_sms".$telephone.$yzm;
		if(!cache()->get($key)){
			$this->goAll("手机验证码出错",1);
		}
		$row=MM("household","household_sender")->selectRow("telephone='".$telephone."'");
		if($row){
			$_SESSION["mhousehold_sender"]=$row["senderid"];
			$code=MM("household","household_sender")->setCode($row);
			$this->goAll("登录成功",0,array(
				"code"=>$code
			));
		}else{
			$senderid=MM("household","household_sender")->insert(array(
				"telephone"=>$telephone
			));
			$_SESSION["mhousehold_sender"]=$senderid;
			$code=MM("household","household_sender")->setCode(array(
				"senderid"=>$senderid,
				"telephone"=>$telephone
			));
			//通知网站
			M("site_msg")->add([
				"tablename"=>"household_sender",
				"content"=>"有家政服务员工注册快去审核吧了"
			]);
			$this->goAll("登录成功",0,array(
				"code"=>$code
			));
		}
		
	}
	
	
}
?>