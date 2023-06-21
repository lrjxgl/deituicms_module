<?php
class ftp_hostControl extends skymvc{
	public function __construct(){
		parent::__construct();
		session_write_close();
	}
	public function onDefault(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$list=M("mod_ftp_host")->select(array(
			"where"=>" userid=".$userid,
			"fields"=>"ftpid,title"
		));
		$this->smarty->goAssign(array(
			"list"=>$list
		));
	}
	public function onSave(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$title=post("title","h");
		$data=$_POST;
		unset($data["title"]);
		$ftpData=arr2str($data);
		//测试连接
		if(!$conn_id=@ftp_connect($data["host"],$data["port"])){
			$this->goAll("连接主机失败",1);
		}
		if(!@ftp_login($conn_id,$data["user"],$data["pass"])){
			$this->goAll("用户登录失败",1);
		}
		
		M("mod_ftp_host")->insert(array(
			"userid"=>$userid,
			"title"=>$title,
			"ftpdata"=>$ftpData
		));
		$this->goAll("保存成功");
	}
}