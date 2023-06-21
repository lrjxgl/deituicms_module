<?php
class wmo2o_adminControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("wmo2o_admin/index.html");
	}
	public function onPassword(){
		$this->smarty->display("wmo2o_admin/password.html");
	}
	public function onPasswordSave(){
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
			"password"=>umd5($password.$salt),
			"salt"=>$salt,
			 
		);
		M("mod_wmo2o_admin")->update($data,"shopid=".SHOPID);
		$this->goAll("密码修改成功");
	}
	
}