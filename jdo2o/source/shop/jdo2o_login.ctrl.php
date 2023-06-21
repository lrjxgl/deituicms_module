<?php
class jdo2o_loginControl extends skymvc{
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		$this->smarty->display("jdo2o_login/index.html");
	}
	public function onSave(){
		$adminname=post("adminname","h");
		$password=post("password","h");
		$row=M("mod_jdo2o_admin")->selectRow("adminname='$adminname'");
		if(empty($row)){
			$this->goAll("账号或者密码出错",1);
		}
		if($row["password"]!=umd5($password.$row["salt"])){
			$this->goAll("账号或者密码出错",1);
		}
		$_SESSION['mjdo2o_shop_admin']=array(
			"shopid"=>$row["shopid"],
			"adminname"=>$row["adminname"],
			"xlasttime"=>$row["lasttime"],
			"lasttime"=>date("Y-m-d H:i:s"),
			"adminid"=>$row["adminid"]
		);
		$authcode=MM("jdo2o","jdo2o_login")->setCode($row);
		$rdata=array(
			"authcode"=>$authcode
		);
		$this->goAll("登录成功",0,$rdata);
	}
	
	public function onLogout(){
		$_SESSION["mjdo2o_shop_admin"]=NULL;
		unset($_SESSION["mjdo2o_shop_admin"]);
		$this->goAll("注销成功");
	}
}