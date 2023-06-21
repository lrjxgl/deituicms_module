<?php
class fenlei_hongbaoControl extends skymvc{
	
	public function __construct(){
		parent::__construct();
	}
	public function onDefault(){
		
	}
	
	public  function onMy(){
		M("login")->checkLogin();
		$userid=M("login")->userid;
		$hbuser=MM("hongbao","hongbao_user")->get($userid);
		$hbList=M("mod_hongbao_user_moneylog")->select(array(
			"where"=>"userid=".$userid,
			"order"=>"id DESC"
		));
		$this->smarty->goAssign(array(
			"hbuser"=>$hbuser,
			"hbList"=>$hbList
		));
		$this->smarty->display("fenlei_hongbao/my.html");
	}
	
	
}
?>