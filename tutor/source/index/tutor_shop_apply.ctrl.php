<?php
class tutor_shop_applyControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onInit(){
		M("login")->checkLogin();
	}
	public function onDefault(){
		$userid=M("login")->userid;
		$apply=M("mod_tutor_shop_apply")->selectRow("userid=".$userid."   ");
		$shop=M("mod_tutor_shop")->selectRow("userid=".$userid);
		if($shop){
			$this->goAll("你已经成功入驻了",1,0,"/module.php?m=tutor");
		}
		$this->smarty->goAssign(array(
			"apply"=>$apply
		));
		$this->smarty->display("tutor_shop_apply/index.html");
	}
	
	public function onSendSms(){
		$telephone=get_post('telephone','h');
		if(!is_tel($telephone)){
			$this->goall("请输入正确手机号码",1);
		}
		 
		$yzm=rand(1111,9999);
		$exkey="tutorshopapplysms".$telephone;
		$key="tutorshopapplysms".$telephone.$yzm;
		$t=cache()->get($exkey);
		if($t){
			$this->goall("请过".(60-(time()-$t))."秒再发送",1);
		}
		
		 
		$content="【".SMS_QIANMING."】验证码：".$yzm."，请您5分钟内完成注册";
		$content=array(
			"content"=>$content,
			"code"=>$yzm,
			"tpl"=>"reg"
		);
		if(defined("SMS_TEST") && SMS_TEST){
			$res=true;
			cache()->set($key,1,300);
			 
			$this->goAll("验证码".$yzm);
		}
		$res=M("sms")->sendSms($telephone,$content);
		if($res){
			cache()->set($key,1,300);
			cache()->set($exkey,time(),60);
			$this->goAll("短信已发送，请在5分钟内验证注册");
		}else{
			$this->goAll("短信发送失败",1);
		}
	}
	
	public function onSave(){
		$yzm=post('yzm','h');
		$telephone=post('telephone','h');
		$key="tutorshopapplysms".$telephone.$yzm;
		if(!cache()->get($key)){
			$this->goAll("手机验证码出错",1);
		}
		$userid=M("login")->userid;
		$apply=M("mod_tutor_shop_apply")->selectRow("userid=".$userid);
		if($apply){
			$this->goAll("您已经提交申请了，请等待通知",1);
		}
		$data=M("mod_tutor_shop_apply")->postData();
		$chkarr=array("nickname","telephone","userno","address");
		foreach($chkarr as $v){
			if(empty($data[$v])){
				$this->goAll("请填写完整内容".$v,1);
			}
		}
		$data["userid"]=$userid;
		M("mod_tutor_shop_apply")->insert($data);
		$this->goAll("申请成功，请等待审核");
	}
	
}