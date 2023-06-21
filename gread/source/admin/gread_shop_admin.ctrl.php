<?php 
class gread_shop_adminControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	
	public function onDefault(){
		$shopid=get_post("shopid","i");
		$data=M("mod_gread_shop_admin")->selectRow("shopid=".$shopid);
		$this->smarty->goAssign(array(
			"data"=>$data,
			"shopid"=>$shopid
		));
		$this->smarty->display("gread_shop_admin/index.html");
	}
	
	public function onSave(){
		$shopid=get_post("shopid","i");
		$username=post("username","h");
		$telephone=post("telephone","h");
		$salt=rand(1000,9999);
		$password=umd5(post("password").$salt);
		$row=M("mod_gread_shop_admin")->selectRow("shopid=".$shopid);
		if($row){
			M("mod_gread_shop_admin")->update(array(
				"username"=>$username,
				"telephone"=>$telephone,
				"salt"=>$salt,
				"password"=>$password
			),"shopid=".$shopid);
		}else{
			M("mod_gread_shop_admin")->insert(array(
				"username"=>$username,
				"telephone"=>$telephone,
				"salt"=>$salt,
				"password"=>$password,
				"shopid"=>$shopid
			));
		}
		
		$this->goAll("保存");
	}
}