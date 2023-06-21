<?php
class flk_adminControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("flk_admin/index.html");
	}
	public function onPassword(){
		$this->smarty->goAssign(array(
			"data"=>""
		));
		$this->smarty->display("flk_admin/password.html");
	}
	public function onPasswordSave(){
		$oldpassword=post("oldpassword","h");
		$password=post("password","h");
		$password2=post("password2","h");
		$salt=rand(1000,9999);
		$admin=M("mod_flk_admin")->selectRow("adminid=".$_SESSION["mflk_shop_admin"]["adminid"]);
		if($admin["password"]!=umd5($oldpassword.$admin["salt"])){
			$this->goAll("旧密码出错",1);
		}
		if(empty($password)){
			$this->goAll("请输入密码",1);
		}
		if($password!=$password2){
			$this->goAll("密码不一致",1);
		}
		$data=array(
			"password"=>umd5($password.$salt),
			"salt"=>$salt,
		);
		M("mod_flk_admin")->update($data,"shopid=".SHOPID);
		$this->goAll("密码修改成功");
	}
	
}