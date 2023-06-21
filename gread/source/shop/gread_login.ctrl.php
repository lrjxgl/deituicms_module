<?php
class gread_loginControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	} 
	
	public function onDefault(){
		 
		$this->smarty->display("gread_login/index.html");
	}
	
	public function onLogin(){
		$telephone=post('telephone');
		$password=post('password','h');
		$row=M("mod_gread_shop_admin")->selectRow("telephone='".$telephone."' ");
		if(!$row){
			$this->goAll("登录失败",1);
		}
		if($row){
			
			if(umd5($password.$row['salt'])==$row['password']){
				unset($row['password']);
				$_SESSION['ssgreadshop']=$row;
				$this->goAll("登录成功");
			}else{
				$this->goAll("登录失败",1);
			}
			
		}
		
	}
	
}
?>