<?php
class csc_shop_paypwdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$user=M("mod_csc_shop_safephone")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,telephone"
		));
		if(!$user){
			$this->goAll("请先设置安全手机",1,0,"/moduleshop.php?m=csc_shop_sefephone");
		}
		$this->smarty->goassign(array(
		
		));
		$this->smarty->display("csc_shop_paypwd/index.html");
	}
	
	public function onsendsms(){
		$user=M("mod_csc_shop_safephone")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,telephone"
		));
		$yzm=rand(1111,9999);
		$telephone=$user["telephone"];
		$exkey="csc_shop_paypwd_sms_expire".$telephone;
		$key="csc_shop_paypwd_sms".$telephone.$yzm;
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
		if($res){
			cache()->set($key,1,300);
			cache()->set($exkey,time(),60);
			$this->goAll("短信已发送，请在5分钟内验证注册");
		}else{
			$this->goAll("短信发送失败",1);
		}
	}
	
	public function onSave(){
		$yzm=get_post('yzm','h');
		$user=M("mod_csc_shop_safephone")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,telephone"
		));
		$telephone=$user["telephone"];
		$key="csc_shop_paypwd_sms".$telephone.$yzm;
		
		if(!cache()->get($key)){
			$this->goAll("短信验证码认证失败",1);
		}		 
		$password=post("paypwd","h");
		$data=array(
			"paypwd"=>umd5($password)
		);
		MM("csc","csc_shop_paypwd")->get(SHOPID);
		MM("csc","csc_shop_paypwd")->update($data,"shopid=".SHOPID);
		$this->goAll("支付密码修改成功");
	}
	
}
?>