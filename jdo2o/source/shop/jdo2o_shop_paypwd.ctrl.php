<?php
class jdo2o_shop_paypwdControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->goassign(array(
		
		));
		$this->smarty->display("jdo2o_shop_paypwd/index.html");
	}
	public function onSave(){
		$oldpassword=post("oldpassword","h");
		$password=post("password","h");
		$password2=post("password2","h");
		$salt=rand(1000,9999);
		$admin=M("mod_jdo2o_admin")->selectRow("adminid=".$_SESSION["mjdo2o_shop_admin"]["adminid"]);
		if($admin["password"]!=umd5($oldpassword.$admin["salt"])){
			$this->goAll("登录密码出错",1);
		}
		if(empty($password)){
			$this->goAll("请输入密码",1);
		}
		if($password!=$password2){
			$this->goAll("密码不一致",1);
		}
		$data=array(
			"paypwd"=>umd5($password)
		);
		MM("jdo2o","jdo2o_shop_paypwd")->get(SHOPID);
		MM("jdo2o","jdo2o_shop_paypwd")->update($data,"shopid=".SHOPID);
		$this->goAll("支付密码修改成功");
	}
	
}
?>