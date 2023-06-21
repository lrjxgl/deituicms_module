<?php
class olprint_shop_paypwdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$user=M("mod_olprint_shop_safephone")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,telephone"
		));
		$this->smarty->goassign(array(
			"user"=>$user
		));
		$this->smarty->display("olprint_shop_paypwd/index.html");
	}
	public function onsendsms(){
		$user=M("mod_olprint_shop_safephone")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,telephone"
		));
		$yzm=rand(1111,9999);
		$telephone=$user["telephone"];
		$exkey="olprint_shop_paypwdAdd".$telephone;
		$key="olprint_shop_paypwdAdd_sms".$telephone.$yzm;
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
		$user=M("mod_olprint_shop_safephone")->selectRow(array(
			"where"=>" shopid=".SHOPID,
			"fields"=>"shopid,telephone"
		));
		$telephone=$user["telephone"];
		$key="olprint_shop_paypwdAdd_sms".$telephone.$yzm;
		if(cache()->get($key)){		 
			$password=post("password","h");
			$password2=post("password2","h");
			$salt=rand(1000,9999);
			$admin=M("mod_olprint_admin")->selectRow("adminid=".$_SESSION["molprint_shop_admin"]["adminid"]);
			 
			if(empty($password)){
				$this->goAll("请输入密码",1);
			}
			 
			$data=array(
				"paypwd"=>umd5($password)
			);
			MM("olprint","olprint_shop_paypwd")->get(SHOPID);
			MM("olprint","olprint_shop_paypwd")->update($data,"shopid=".SHOPID);
		}
		$this->goAll("支付密码修改成功");
	}
	
}
?>