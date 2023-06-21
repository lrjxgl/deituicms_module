<?php
class tutor_shop_paypwdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$safephone=M("mod_tutor_shop_safephone")->selectRow("shopid=".SHOPID);
		if(!$safephone){
			$this->goAll("请先绑定安全手机",1,0,"/moduleshop.php?m=tutor_shop_safephone");
		}
		$this->smarty->goassign(array(
			"telephone"=>$safephone["telephone"]
		));
		$this->smarty->display("tutor_shop_paypwd/index.html");
	}
	
	public function onSendSms(){
		$safephone=M("mod_tutor_shop_safephone")->selectRow("shopid=".SHOPID);
		if(!$safephone){
			$this->goAll("请先绑定安全手机",1);
		}
		$telephone=$safephone["telephone"];
		 
		$yzm=rand(1111,9999);
		$exkey="tutor_shop_paypwd_sendsms".$telephone;
		$key="tutor_shop_paypwd_sendsms".$telephone.$yzm;
		$t=cache()->get($exkey);
		if($t){
			$this->goall("请过".(60-(time()-$t))."秒再发送",1);
		}
		
		 
		$content="【".SMS_QIANMING."】验证码：".$yzm."，请您5分钟内完成绑定";
		$content=array(
			"content"=>$content,
			"code"=>$yzm,
			"tpl"=>"reg"
		);
		$res=M("sms")->sendSms($telephone,$content);
		if(TESTMODEL==1){
			cache()->set($key,1,300);
			cache()->set($exkey,time(),60);
			$this->goAll("短信已发送".$yzm);
		}
		if($res){
			cache()->set($key,1,300);
			cache()->set($exkey,time(),60);
			$this->goAll("短信已发送，请在5分钟内验证注册");
		}else{
			$this->goAll("短信发送失败",1);
		}
	}
	
	public function onSave(){
		$yzm=post("yzm","h");
		$safephone=M("mod_tutor_shop_safephone")->selectRow("shopid=".SHOPID);
		$telephone=$safephone["telephone"];
		$key="tutor_shop_paypwd_sendsms".$telephone.$yzm;
		if(!cache()->get($key)){
			$this->goAll("验证码出错",1);
		} 
		$password=post("password","h");
		$password2=post("password2","h");
		$salt=rand(1000,9999);
		 
		if(empty($password)){
			$this->goAll("请输入密码",1);
		}
		if($password!=$password2){
			$this->goAll("密码不一致",1);
		}
		$data=array(
			"paypwd"=>umd5($password)
		);
		MM("tutor","tutor_shop_paypwd")->get(SHOPID);
		MM("tutor","tutor_shop_paypwd")->update($data,"shopid=".SHOPID);
		$this->goAll("支付密码修改成功");
	}
	
}
?>