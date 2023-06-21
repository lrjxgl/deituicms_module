<?php
class exue_shop_safephoneControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$data=M("mod_exue_shop_safephone")->selectRow("shopid=".SHOPID);
		$this->smarty->goAssign(array(
			"data"=>$data
		));
		$this->smarty->display("exue_shop_safephone/index.html");
	}
	public function onSendSms(){
		$telephone=get_post('telephone','h');
		if(!is_tel($telephone)){
			$this->goall("请输入正确手机号码",1);
		}
		if(M("mod_exue_shop_safephone")->select(array("where"=>"telephone='".$telephone."' "))){
			$this->goall("手机已经存在了",1);
		}
		$yzm=rand(1111,9999);
		$exkey="exueshopsafephonesms".$telephone;
		$key="exueshopsafephonesms".$telephone.$yzm;
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
		$key="exueshopsafephonesms".$telephone.$yzm;
		if(!cache()->get($key)){
			$this->goAll("手机验证码出错",1);
		}
		$row=M("mod_exue_shop_safephone")->selectRow("shopid=".SHOPID);
		if($row){
			M("mod_exue_shop_safephone")->update(array(
				"telephone"=>$telephone
			),"shopid=".SHOPID);
		}else{
			$data=array(
				"shopid"=>SHOPID,
				"telephone"=>$telephone
			);
			M("mod_exue_shop_safephone")->insert($data);
		}
		
		$this->goAll("申请成功，请等待审核");
	}
	
}