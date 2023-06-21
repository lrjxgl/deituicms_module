<?php
class csc_sender_paypwdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$sender=M("mod_csc_sender")->selectRow(array(
			"where"=>" senderid=".SENDERID,
			"fields"=>"senderid,telephone"
		));
		$this->smarty->goAssign(array(
			"sender"=>$sender
		));
		$this->smarty->display("csc_sender_paypwd/index.html");
	}
	public function onsendsms(){
		$user=M("mod_csc_sender")->selectRow(array(
			"where"=>" senderid=".SENDERID,
			"fields"=>"senderid,telephone"
		));
		$yzm=rand(1111,9999);
		$telephone=$user["telephone"];
		$exkey="csc_sender_paypwd_sms_ex".$telephone;
		$key="csc_sender_paypwd_sms".$telephone.$yzm;
		$t=cache()->get($exkey);
		if($t){
			$this->goall("请过".(60-(time()-$t))."秒再发送",1);
		}
		
		 
		$content="【".SMS_QIANMING."】验证码：".$yzm."，请您5分钟内完成验证";
		$content=array(
			"code"=>$yzm,
			"tpl"=>"code",
			"content"=>$content
		);
		$res=M("sms")->sendSms($telephone,$content);
		$res=true;
		if($res){
			cache()->set($key,1,300);
			cache()->set($exkey,time(),60);
			$this->goAll("短信已发送，请在5分钟内验证注册".$yzm);
		}else{
			$this->goAll("短信发送失败",1);
		}
	}
	
	public function onSave(){
		$yzm=get_post('yzm','h');
		$user=M("mod_csc_sender")->selectRow(array(
			"where"=>" senderid=".SENDERID,
			"fields"=>"senderid,telephone"
		));
		$telephone=$user["telephone"];
		$key="csc_sender_paypwd_sms".$telephone.$yzm;
		
		if(cache()->get($key)){
			$password=post("password","h");
			$row=M("mod_csc_sender_paypwd")->selectRow("senderid=".SENDERID);
			if(!$row){
				M("mod_csc_sender_paypwd")->insert(array(
					"senderid"=>SENDERID,
					"paypwd"=>umd5($password)
				));
			}else{
				M("mod_csc_sender_paypwd")->update(array(
					"paypwd"=>umd5($password)
				),"senderid=".SENDERID);
			}
			
			$this->goAll("密码修改成功");
		}else{
			$this->goAll("短信验证码认证失败",1);
		}
		 
		
		 
	}
	
}