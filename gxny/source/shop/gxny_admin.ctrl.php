<?php
class gxny_adminControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("gxny_admin/index.html");
	}
	public function onPassword(){
		$this->smarty->display("gxny_admin/password.html");
	}
	public function onPasswordSave(){
		$password=post("password","h");
		$password2=post("password2","h");
		$salt=rand(10000,9999);
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
		M("mod_gxny_admin")->update($data,"shopid=".SHOPID);
		$this->goAll("密码修改成功");
	}
	
}